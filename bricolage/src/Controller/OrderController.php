<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\LineOrder;
use App\Repository\ProductRepository;
use App\Repository\PromoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/order', name: 'order_')]
class OrderController extends AbstractController
{
    // Sets the start time of the payment
    private function setPaymentStartTime(SessionInterface $session): void
    {
        $timeLimit = 15 * 60; // 15 minutes en secondes
        $paymentEndTime = time() + $timeLimit;
        $session->set('payment_end_time', $paymentEndTime);
    }

    // Checks whether the 15-minute time limit has elapsed
    private function isPaymentTimeExpired(Order $order, EntityManagerInterface $em, SessionInterface $session): bool
    {
        $paymentEndTime = $session->get('payment_end_time', 0);
        $currentTime = time();
        if ($currentTime >= $paymentEndTime) {
            // Restore stock when payment time is expired
            $this->restoreStock($order, $em, $session);
            // Reset payment start time
            $this->setPaymentStartTime($session);
            return true;
        }
        return false;
    }

    // Restore stock if payment is not confirmed within a given timeframe
    private function restoreStock(Order $order, EntityManagerInterface $em, SessionInterface $session): void
    {
        $reservedStock = $session->get('reserved_stock', []);
        foreach ($order->getLineOrders() as $lineOrder) {
            $product = $lineOrder->getProduct();
            $productId = $product->getId();
            if (array_key_exists($productId, $reservedStock)) {
                $currentStock = $product->getStock();
                $product->setStock($currentStock + $reservedStock[$productId]);
                unset($reservedStock[$productId]);
                $em->persist($product);
            }
        }
        $session->set('reserved_stock', $reservedStock);
        $em->flush();
    }

    #[Route('/list', name: 'list')]
    public function listOrders(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $orders = $em->getRepository(Order::class)->findBy(['user' => $user], ['dateOrder' => 'DESC']);

        return $this->render('pages/order/list.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/details/{id}', name: 'details')]
    public function orderDetails(Order $order): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if ($order->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You are not allowed to view details of this order.');
        }

        $lineOrders = $order->getLineOrders();

        return $this->render('pages/order/details.html.twig', [
            'order' => $order,
            'lineOrders' => $lineOrders,
        ]);
    }

    #[Route('/add', name: 'add')]
    #[IsGranted("ROLE_USER")]
    public function add(SessionInterface $session, ProductRepository $productRepository, PromoRepository $promoRepository, EntityManagerInterface $em): Response
    {
        $this->setPaymentStartTime($session);

        $cart = $session->get('cart', []);

        if ($cart === []) {
            $this->addFlash('danger', 'Your cart is empty');
            return $this->redirectToRoute('home');
        }

        $user = $this->getUser();
        $orderReference = $session->get('order_reference');
        $existingOrder = null;

        if ($orderReference) {
            $existingOrder = $em->getRepository(Order::class)->findOneBy(['reference' => $orderReference]);
        }

        if ($existingOrder && !in_array('ORDER_PAID', $existingOrder->getStatutOrders(), true) && !in_array('ORDER_CANCELED', $existingOrder->getStatutOrders(), true)) {
            $order = $existingOrder;
            $lineOrders = $order->getLineOrders();
            foreach ($lineOrders as $lineOrder) {
                $product = $lineOrder->getProduct();
                $discountedPrice = $product->getPriceVAT();
                $activePromos = $promoRepository->findActivePromos();
                foreach ($activePromos as $promo) {
                    if ($promo->isActivePromo() && $product->getPromos()->contains($promo)) {
                        $discountedPrice = $product->getPriceVAT() * (1 - $promo->getPercent() / 100);
                        break;
                    }
                }
                $lineOrder->setSellingPrice($discountedPrice);
                $em->persist($lineOrder);
            }

            $total = $order->calculateTotal();
            $order->setTotal($total);
            $em->flush();
        } else {
            $order = new Order();
            $order->setUser($user);
            $order->setReference(uniqid());
            $order->setStatutOrders(['ORDER_IN_PROCESS']);
            $order->setPaymentMode('In process');

            foreach ($cart as $item => $quantity) {
                $product = $productRepository->find($item);

                if (!$product) {
                    continue;
                }

                $lineOrder = new LineOrder();
                $lineOrder->setProduct($product);

                $discountedPrice = $product->getPriceVAT();

                $activePromos = $promoRepository->findActivePromos();
                foreach ($activePromos as $promo) {
                    if ($promo->isActivePromo() && $product->getPromos()->contains($promo)) {
                        $discountedPrice = $product->getPriceVAT() * (1 - $promo->getPercent() / 100);
                        break;
                    }
                }

                $discountedPrice = round($discountedPrice, 2);

                $lineOrder->setSellingPrice($discountedPrice);
                $lineOrder->setQuantity($quantity['quantity']);

                $order->addLineOrder($lineOrder);
            }

            $total = $order->calculateTotal();
            $order->setTotal($total);

            $em->persist($order);
            $em->flush();

            $session->set('order_reference', $order->getReference());
        }

        foreach ($cart as $productId => $cartItem) {
            $lineOrder = $order->getLineOrderByProduct($productId);

            if ($lineOrder) {
                $lineOrderQuantity = $lineOrder->getQuantity();
                $cartQuantity = $cartItem['quantity'];

                if ($lineOrderQuantity !== $cartQuantity) {
                    $lineOrder->setQuantity($cartQuantity);
                    $total = $order->calculateTotal();
                    $order->setTotal($total);

                    $em->persist($lineOrder);
                    $em->persist($order);
                    $em->flush();
                }
            }
        }

        return $this->render('pages/order/pay.html.twig', [
            'order' => $order,
            'lineOrders' => $order->getLineOrders(),
        ]);
    }

    #[Route('/pay/{id}', name: 'pay')]
    public function pay(Order $order = null, EntityManagerInterface $em, Security $security): RedirectResponse|Response
    {
        $user = $security->getUser();

        if (!$order) {
            $this->addFlash('danger', 'Order not found.');
            return $this->redirectToRoute('cart_index');
        }

        if (in_array('ORDER_PAID', $order->getStatutOrders(), true)) {

            $this->addFlash('warning', 'Order already paid.');
            return $this->redirectToRoute('order_details', [
                'id' => $order->getId()
            ]);
        }

        Stripe::setApiKey('sk_test_51OmSi4FS6JbfKYCePGdG2icQrbYCdyh9SS9G0yqbE2OIPp8hYZ9gfFKUHF4uHP2tnfUFjzPBJV3ipsYrXRnA5aS000EsUJvekZ');

        $lineItems = [];
        foreach ($order->getLineOrders() as $lineOrder) {
            $product = $lineOrder->getProduct();
            $basePrice = $product->getPriceVAT();
            $discountedPrice = $basePrice;

            $activePromos = $product->getPromos();
            foreach ($activePromos as $promo) {
                if ($promo->isActivePromo()) {
                    $discountedPrice = $basePrice * (1 - $promo->getPercent() / 100);
                    break;
                }
            }

            $description = sprintf('Original price: %.2f€', $basePrice);
            if ($basePrice !== $discountedPrice) {
                $description .= sprintf(', Promotional price: %.2f€', $discountedPrice);
            }

            $discountedPrice = round($discountedPrice, 2);

            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $product->getNameProduct(),
                        'description' => $description,
                    ],
                    'unit_amount' => $discountedPrice * 100,
                ],
                'quantity' => $lineOrder->getQuantity(),
            ];
        }

        $checkoutSession = Session::create([
            'customer_email' => $user->getEmail(),
            'payment_method_types' => ['card', 'bancontact', 'paypal'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $this->generateUrl('order_success', ['id' => $order->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('order_cancel_payment', ['id' => $order->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        $order->setStripeSessionId($checkoutSession->id);

        $em->persist($order);
        $em->flush();

        return new RedirectResponse($checkoutSession->url);

        return $this->render('pages/order/pay.html.twig', [
            'order' => $order,
            'lineOrders' => $order->getLineOrders(),
            'user' => $user,
        ]);
    }

    #[Route('/success/{id}', name: 'success')]
    public function success(Order $order, SessionInterface $session, EntityManagerInterface $em): Response
    {
        if (in_array('ORDER_PAID', $order->getStatutOrders(), true)) {
            $this->addFlash('warning', 'Order already paid.');
        } else {
            if ($order->getStripeSessionId()) {
                Stripe::setApiKey('sk_test_51OmSi4FS6JbfKYCePGdG2icQrbYCdyh9SS9G0yqbE2OIPp8hYZ9gfFKUHF4uHP2tnfUFjzPBJV3ipsYrXRnA5aS000EsUJvekZ');
                $stripeSession = Session::retrieve($order->getStripeSessionId());

                $paymentMode = $stripeSession->payment_method_types[0] ?? 'Unknown';

                foreach ($order->getLineOrders() as $lineOrder) {
                    $product = $lineOrder->getProduct();
                    $quantity = $lineOrder->getQuantity();

                    if ($product->getStock() < $quantity) {
                        $this->addFlash('danger', 'Insufficient stock for product: ' . $product->getNameProduct());

                        return $this->redirectToRoute('order_error', [
                            'id' => $order->getId()
                        ]);
                    }

                    if ($this->isPaymentTimeExpired($order, $em, $session)) {
                        // Restores stock if the 15-minute time limit has elapsed
                        $this->restoreStock($order, $em, $session);
                        $this->addFlash('warning', 'Payment time expired. Stock has been restored.');

                        return $this->redirectToRoute('order_details', [
                            'id' => $order->getId()
                        ]);
                    }
                }

                // Deduct stock only if payment is successful and time is not expired
                foreach ($order->getLineOrders() as $lineOrder) {
                    $product = $lineOrder->getProduct();
                    $quantity = $lineOrder->getQuantity();
                    $currentStock = $product->getStock();
                    $product->setStock($currentStock - $quantity);
                    $em->persist($product);
                }

                // Enregistre le mode de paiement dans l'objet Order
                $order->setPaymentMode($paymentMode);
                $order->setStatutOrders(['ORDER_PAID']);
                $em->persist($order);
                $em->flush();

                $session->remove('cart');

                $this->addFlash('success', 'Order successfully paid.');
            } else {
                $this->addFlash('danger', 'Payment not completed with Stripe.');
            }
        }

        return $this->redirectToRoute('order_details', ['id' => $order->getId()]);
    }

    #[Route('/error/{id}', name: 'error')]
    public function error(Order $order): Response
    {
        $this->addFlash('danger', 'Payment not completed with Stripe.');

        return $this->redirectToRoute('order_pay', [
            'id' => $order->getId()
        ]);
    }

    #[Route('/cancel/{id}', name: 'cancel', methods: ['GET', 'POST'])]
    public function cancel(Order $order, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if ($order->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You are not allowed to cancel this order.');
        }

        if (!in_array('ORDER_CANCELED', $order->getStatutOrders(), true)) {

            // Restoring the stock for each product in the canceled order
            foreach ($order->getLineOrders() as $lineOrder) {
                $product = $lineOrder->getProduct();
                $quantity = $lineOrder->getQuantity();
                $currentStock = $product->getStock();
                $product->setStock($currentStock + $quantity);
                $em->persist($product);
            }

            $order->setStatutOrders(['ORDER_CANCELED']);

            $em->persist($order);
            $em->flush();

            $this->addFlash('success', 'Order successfully cancelled.');
        } else {
            $this->addFlash('danger', 'This order cannot be cancelled.');
        }

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/cancel_payment/{id}', name: 'cancel_payment', methods: ['GET'])]
    public function cancelPayment(Order $order, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if ($order->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You are not allowed to cancel payment for this order.');
        }

        if (in_array('ORDER_PAID', $order->getStatutOrders(), true)) {
            $this->addFlash('warning', 'Payment already completed.');

            return $this->redirectToRoute('order_details', [
                'id' => $order->getId()
            ]);
        }

        $order->setStatutOrders(['ORDER_IN_PROCESS']);
        $order->setPaymentMode('Unkown');

        $em->persist($order);
        $em->flush();

        $this->addFlash('info', 'Payment canceled successfully.');

        return $this->redirectToRoute('cart_index');
    }
}
