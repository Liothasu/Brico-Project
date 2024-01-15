<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\LineOrder;
use App\Form\PaymentType;
use App\Repository\ProductRepository;
use App\Repository\PromoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/order', name: 'order_')]
class OrderController extends AbstractController
{
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
    public function add(SessionInterface $session, ProductRepository $productRepository, PromoRepository $promoRepository, EntityManagerInterface $em, Request $request): Response
    {
        $cart = $session->get('cart', []);

        if ($cart === []) {
            $this->addFlash('message', 'Your cart is empty');
            return $this->redirectToRoute('home');
        }

        $user = $this->getUser();

        $existingOrder = $em->getRepository(Order::class)->findOneBy([
            'user' => $user,
            'statutOrders' => ['ORDER_PENDING']
        ]);

        if ($existingOrder && !in_array('ORDER_PAID', $existingOrder->getStatutOrders(), true)) {

            if (!$existingOrder->getReference() || $existingOrder->getReference() !== uniqid()) {
                $existingOrder->setReference(uniqid());
                $em->flush();

                dump('Reference Updated to: ' . $existingOrder->getReference());
            } else {
                dump('Using Existing Order without Updating Reference');
            }

            $order = $existingOrder;
        } else {

            $order = new Order();
            $order->setUser($user);
            $order->setReference(uniqid());
            $order->setStatutOrders(['ORDER_IN_PROCESS']);
            $order->setPaymentMode('In process');

            dump('New Order Created with Reference: ' . $order->getReference());

            foreach ($cart as $item => $quantity) {
                $product = $productRepository->find($item);

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

                $lineOrder->setSellingPrice($discountedPrice);

                $lineOrder->setQuantity($quantity['quantity']);

                $order->addLineOrder($lineOrder);
            }

            $total = $order->calculateTotal();
            $order->setTotal($total);

            $em->persist($order);
            $em->flush();
        }

        $this->addFlash('message', 'Commande mise à jour avec succès');

        return $this->render('pages/order/pay.html.twig', [
            'order' => $order,
            'lineOrders' => $order->getLineOrders(),
            'paymentForm' => $this->createForm(PaymentType::class)->createView(),
        ]);
    }

    #[Route('/pay/{id}', name: 'pay')]
    public function pay(Order $order = null, SessionInterface $session, EntityManagerInterface $em, Request $request): Response
    {
        if (!$order) {
            $this->addFlash('error', 'Order not found.');
            return $this->redirectToRoute('cart_index');
        }

        if (in_array('ORDER_PAID', $order->getStatutOrders(), true)) {
            $this->addFlash('message', 'Commande déjà payée.');
            return $this->redirectToRoute('order_details', ['id' => $order->getId()]);
        }

        $form = $this->createForm(PaymentType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paymentData = $form->getData();

            $order->setPaymentMode($paymentData->getPaymentMode());
            $order->setStatutOrders(['ORDER_PAID']);

            $em->persist($order);
            $em->flush();

            $session->remove('cart');

            $this->addFlash('message', 'Payment successful.');

            return $this->redirectToRoute('order_details', [
                'id' => $order->getId()
            ]);
        }

        // $this->addFlash('error', 'Le paiement n\'est pas valide. Veuillez réessayer.');

        return $this->render('pages/order/pay.html.twig', [
            'order' => $order,
            'lineOrders' => $order->getLineOrders(),
            'paymentForm' => $form->createView(),
        ]);
    }

    #[Route('/cancel/{id}', name: 'cancel', methods: ['POST'])]
    public function cancel(Order $order, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if ($order->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You are not allowed to cancel this order.');
        }

        if (!in_array('ORDER_CANCELED', $order->getStatutOrders(), true)) {
            $order->setStatutOrders(['ORDER_CANCELED']);

            $em->persist($order);
            $em->flush();

            $this->addFlash('message', 'Commande annulée avec succès.');
        } else {
            $this->addFlash('message', 'Cette commande ne peut pas être annulée.');
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

        // Mettez à jour le statut de la commande
        $order->setStatutOrders(['ORDER_PENDING']);

        $em->persist($order);
        $em->flush();

        $this->addFlash('message', 'Payment canceled successfully.');

        // Redirigez l'utilisateur vers le panier en cours
        return $this->redirectToRoute('cart_index');
    }
}