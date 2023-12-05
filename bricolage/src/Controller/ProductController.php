<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;

class ProductController extends AbstractController
{
    #[Route('/shop', name: 'product_form')]
    public function product_form(): Response
    {
        $form = $this->createForm(ProductType::class);
        return $this->render('shop/product_form.twig', [
            'form' => $form->createView()
        ]);
    }

    public function ProductCreate(): Response
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);

        return $this->render('shop/product_form.twig', [
            'form' => $form->createView()
        ]);
    }

    public function ProductSave(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            dump($product);die;
        }
        return $this->render('shop/product_form.twig', [
            'form' => $form->createView()
        ]);
    }

    public function ProductSaveOnBD(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($product);
            $entityManager->flush();
        }
        return $this->render('shop/product_form.twig', [
            'form' => $form->createView()
        ]);
    }
}
