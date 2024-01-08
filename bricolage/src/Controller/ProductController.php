<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/shop', name: 'product_')]
class ProductController extends AbstractController
{
    #[Route('/all', name: 'all')]
    public function yourAction(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        return $this->render('pages/product/list.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/{slug}', name: 'list')]
    public function list(Product $product): Response
    {
        return $this->render('pages/shop/product_list.html.twig', compact('product'));
    }
}
