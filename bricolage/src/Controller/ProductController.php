<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Form\FilterType;
use App\Model\FilterData;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\PromoRepository;
use Symfony\Component\HttpFoundation\Request;

#[Route('/shop', name: 'product_')]
class ProductController extends AbstractController
{
    #[Route('/all', name: 'all')]
    public function all(ProductRepository $productRepository, PromoRepository $promoRepository, CategoryRepository $categoryRepository, Request $request): Response
    {
        $filterData = new FilterData();
        $form = $this->createForm(FilterType::class, $filterData);
        $form->handleRequest($request);

        $products = $productRepository->findAll();
        $activePromos = $promoRepository->findActivePromos();
        $discountedPrices = [];

        $categories = $categoryRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $filterData->page = $request->query->getInt('page', 1);
            $products = $productRepository->findByFilter($filterData);
        }

        foreach ($activePromos as $promo) {
            if ($promo->isActivePromo()) {
                foreach ($products as $product) {
                    $discountedPrice = $product->getPriceVAT() * (1 - $promo->getPercent() / 100);
                    $discountedPrices[$product->getId()] = $discountedPrice;
                }
            }
        }

        return $this->render('pages/product/list.html.twig', [
            'filterForm' => $form->createView(),
            'products' => $products,
            'activePromos' => $activePromos,
            'discountedPrices' => $discountedPrices,
            'categories' => $categories,
        ]);
    }

    #[Route('/category/{slug}', name: 'category')]
    public function category(Category $category, PromoRepository $promoRepository, ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy(['category' => $category]);
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

        return $this->render('pages/category/list.html.twig', [
            'products' => $products,
            'activePromos' => $activePromos,
            'discountedPrices' => $discountedPrices,
            'category' => $category,
        ]);
    }

    #[Route('/detail/{id}', name: 'detail')]
    public function detail(Product $product, PromoRepository $promoRepository): Response
    {
        $activePromos = $promoRepository->findActivePromos();
        $discountedPrices = [];

        foreach ($activePromos as $promo) {
            if ($promo->isActivePromo()) {
                $discountedPrice = $product->getPriceVAT() * (1 - $promo->getPercent() / 100);
                $discountedPrices[$product->getId()] = $discountedPrice;
            }
        }

        return $this->render('pages/product/detail.html.twig', [
            'product' => $product,
            'activePromos' => $activePromos,
            'discountedPrices' => $discountedPrices,
        ]);
    }
}
