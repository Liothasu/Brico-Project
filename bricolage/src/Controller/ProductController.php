<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/shop', name: 'product_')]
class ProductController extends AbstractController
{
    #[Route('/{slug}', name: 'list')]
    public function list(Product $product): Response
    {
        return $this->render('pages/shop/product_list.html.twig', compact('product'));
    }
}
