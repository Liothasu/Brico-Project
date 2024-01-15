<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\PromoRepository;
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
    public function add(Product $product, SessionInterface $session, PromoRepository $promoRepository)
    {
        $id = $product->getId();

        $cart = $session->get('cart', []);

        if (empty($cart[$id])) {
            $cart[$id] = [
                'quantity' => 1,
                'discountedPrice' => $product->getPriceVAT(),
            ];
        } else {
            $cart[$id]['quantity']++;

            $activePromos = $promoRepository->findActivePromos();
            $discountedPrice = $product->getPriceVAT();

            foreach ($activePromos as $promo) {
                if ($promo->isActivePromo() && $product->getPromos()->contains($promo)) {
                    $discountedPrice = $product->getPriceVAT() * (1 - $promo->getPercent() / 100);
                    break;
                }
            }

            $cart[$id]['discountedPrice'] = $discountedPrice;
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/remove/{id}', name: 'remove')]
    public function remove(Product $product, SessionInterface $session)
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

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Product $product, SessionInterface $session)
    {
        $id = $product->getId();

        $cart = $session->get('cart', []);

        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/empty', name: 'empty')]
    public function empty(SessionInterface $session)
    {
        $session->remove('cart');

        return $this->redirectToRoute('cart_index');
    }
}
