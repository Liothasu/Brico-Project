<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\ProductRepository;
use App\Repository\PromoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchBarController extends AbstractController
{
    #[Route('/search-bar', name: 'search_bar')]
    public function index(Request $request, ProductRepository $productRepository, PromoRepository $promoRepository): Response 
    {
        $searchForm = $this->createForm(SearchType::class);
        $searchForm->handleRequest($request);

        $products = [];

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $searchData = $searchForm->getData();
            $searchData->page = $request->query->getInt('page', 1);
            $products = $productRepository->findBySearch($searchData);

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

            $categories = $productRepository->findAllCategories();

            return $this->render('pages/product/list.html.twig', [
                'searchForm' => $searchForm->createView(),
                'products' => $products,
                'activePromos' => $activePromos,
                'discountedPrices' => $discountedPrices,
                'categories' => $categories,
            ]);
        }

        return $this->render('_partials/_search_data.html.twig', [
            'searchForm' => $searchForm->createView(),
        ]);
    }
}
