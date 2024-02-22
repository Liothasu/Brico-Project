<?php

namespace App\Controller;

use App\Entity\LineOrder;
use App\Entity\Order;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\PromoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cart', name: 'cart_')]
class CartController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(SessionInterface $session, ProductRepository $productRepository, PromoRepository $promoRepository): Response
    {
        $cart = $session->get('cart', []);

        $data = [];
        $total = 0;

        foreach ($cart as $id => $item) {
            $product = $productRepository->find($id);

            $data[] = [
                'product' => $product,
                'quantity' => $item['quantity']
            ];

            $discountedPrice = $item['discountedPrice'];

            $activePromos = $promoRepository->findActivePromos();
            foreach ($activePromos as $promo) {
                if ($promo->isActivePromo() && $product->getPromos()->contains($promo)) {
                    $discountedPrice = $product->getPriceVAT() * (1 - $promo->getPercent() / 100);
                    break;
                }
            }

            $total += ($discountedPrice * $item['quantity']);
        }

        return $this->render('pages/cart/index.html.twig', compact('data', 'total'));
    }

    #[Route('/add/{id}', name: 'add')]
    public function add(Product $product, SessionInterface $session, PromoRepository $promoRepository, EntityManagerInterface $em)
    {
        $id = $product->getId();
        $cart = $session->get('cart', []);

        if (empty($cart[$id])) {
            $cart[$id] = [
                'quantity' => 1,
                'discountedPrice' => $product->getPriceVAT(),
            ];
        } else {
            if ($product->getStock() <= $cart[$id]['quantity']) {
                $this->addFlash('message', 'Insufficient stock for product: ' . $product->getNameProduct());
                return $this->redirectToRoute('cart_index');
            }

            $cart[$id]['quantity']++;

            $discountedPrice = $product->getPriceVAT();
            $activePromos = $promoRepository->findActivePromos();

            foreach ($activePromos as $promo) {
                if ($promo->isActivePromo() && $product->getPromos()->contains($promo)) {
                    $discountedPrice = $product->getPriceVAT() * (1 - $promo->getPercent() / 100);
                    break;
                }
            }

            $cart[$id]['discountedPrice'] = $discountedPrice;
        }

        $session->set('cart', $cart);

        $orderReference = $session->get('order_reference');
        if ($orderReference) {
            $existingOrder = $em->getRepository(Order::class)->findOneBy(['reference' => $orderReference]);
            if ($existingOrder) {
                foreach ($existingOrder->getLineOrders() as $lineOrder) {
                    if (isset($cart[$lineOrder->getProduct()->getId()])) {
                        $lineOrder->setQuantity($cart[$lineOrder->getProduct()->getId()]['quantity']);
                        $lineOrder->setSellingPrice($cart[$lineOrder->getProduct()->getId()]['discountedPrice']);
                        unset($cart[$lineOrder->getProduct()->getId()]);
                    } else {
                        $existingOrder->removeLineOrder($lineOrder);
                    }
                }

                foreach ($cart as $productId => $cartItem) {
                    $productToAdd = $em->getRepository(Product::class)->find($productId);
                    $lineOrder = new LineOrder();
                    $lineOrder->setProduct($productToAdd);
                    $lineOrder->setQuantity($cartItem['quantity']);
                    $lineOrder->setSellingPrice($cartItem['discountedPrice']);
                    $existingOrder->addLineOrder($lineOrder);
                }

                $total = $existingOrder->calculateTotal();
                $existingOrder->setTotal($total);

                $em->persist($existingOrder);
                $em->flush();
            }
        }

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/remove/{id}', name: 'remove')]
    public function remove(Product $product, SessionInterface $session, EntityManagerInterface $em)
    {
        $id = $product->getId();

        $cart = $session->get('cart', []);

        if (!empty($cart[$id])) {
            if ($cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity']--;
            } else {
                unset($cart[$id]);
            }
        }

        $session->set('cart', $cart);

        $orderReference = $session->get('order_reference');
        if ($orderReference) {
            $existingOrder = $em->getRepository(Order::class)->findOneBy(['reference' => $orderReference]);

            if ($existingOrder) {
                foreach ($existingOrder->getLineOrders() as $lineOrder) {
                    if ($lineOrder->getProduct()->getId() == $id) {
                        if ($lineOrder->getQuantity() > 1) {
                            $lineOrder->setQuantity($lineOrder->getQuantity() - 1);
                        } else {
                            $existingOrder->removeLineOrder($lineOrder);
                        }

                        $total = $existingOrder->calculateTotal();
                        $existingOrder->setTotal($total);

                        $em->persist($existingOrder);
                        $em->flush();
                    }
                }
            }
        }

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Product $product, SessionInterface $session, EntityManagerInterface $entityManager)
    {
        $id = $product->getId();
        $cart = $session->get('cart', []);

        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }

        $session->set('cart', $cart);

        $orderReference = $session->get('order_reference');
        if ($orderReference) {
            $existingOrder = $entityManager->getRepository(Order::class)->findOneBy(['reference' => $orderReference]);

            if ($existingOrder) {
                foreach ($existingOrder->getLineOrders() as $lineOrder) {
                    if ($lineOrder->getProduct()->getId() === $id) {
                        $existingOrder->removeLineOrder($lineOrder);
                        break;
                    }
                }

                $total = $existingOrder->calculateTotal();
                $existingOrder->setTotal($total);

                $entityManager->persist($existingOrder);
                $entityManager->flush();
            }
        }

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/empty', name: 'empty')]
    public function empty(SessionInterface $session, EntityManagerInterface $em)
    {
        $orderReference = $session->get('order_reference');

        if ($orderReference) {
            $order = $em->getRepository(Order::class)->findOneBy(['reference' => $orderReference]);

            if ($order) {
                $em->remove($order);
                $em->flush();
            }

            $session->remove('order_reference');
        }

        $session->remove('cart');

        return $this->redirectToRoute('cart_index');
    }
}
