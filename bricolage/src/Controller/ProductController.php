<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\PromoRepository;

#[Route('/shop', name: 'product_')]
class ProductController extends AbstractController
{
    #[Route('/all', name: 'all')]
    public function all(ProductRepository $productRepository, PromoRepository $promoRepository): Response
    {
        $products = $productRepository->findAll();
        $activePromos = $promoRepository->findActivePromos();
        $discountedPrices = [];

        foreach ($activePromos as $promo) {
            if ($promo->isActivePromo()) {
                foreach ($products as $product) {
                    $discountedPrice = $product->getPriceVAT() * (1 - $promo->getPercent() / 100);
                    $discountedPrices[$product->getId()] = $discountedPrice;
                }
            }
        }

        return $this->render('pages/product/list.html.twig', [
            'products' => $products,
            'activePromos' => $activePromos,
            'discountedPrices' => $discountedPrices,
        ]);
    }

    #[Route('/{slug}', name: 'list')]
    public function list(Product $product): Response
    {
        return $this->render('pages/shop/product_list.html.twig', compact('product'));
    }
}
