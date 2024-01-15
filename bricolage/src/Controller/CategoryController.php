<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\PromoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category', name: 'category_')]
class CategoryController extends AbstractController
{
    #[Route('/{slug}', name: 'list')]
    public function list(Category $category, PromoRepository $promoRepository): Response
    {
        $products = $category->getProducts();
        $activePromos = $promoRepository->findActivePromos();

        $discountedPrices = [];

        foreach ($products as $product) {
            foreach ($activePromos as $promo) {
                if ($promo->isActivePromo() && $product->getPromos()->contains($promo)) {
                    $discountedPrice = $product->getPriceVAT() * (1 - $promo->getPercent() / 100);
                    $discountedPrices[$product->getId()] = $discountedPrice;
                    break; 
                }
            }
        }

        return $this->render('pages/category/list.html.twig', [
            'category' => $category,
            'products' => $products,
            'discountedPrices' => $discountedPrices,
        ]);
    }
}
