<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/category', name: 'category_')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'form')]
    public function form(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form=$this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        $categoryRepository = $entityManager->getRepository(Category::class);
        $cat = $categoryRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) 
        {
           $entityManager->persist($category);
           $entityManager->flush();
        }
 
        return $this->render('pages/category/form.html.twig', [
            'form' => $form->createView(),
            'cat' => $cat,
        ]);
    }

    #[Route('/{slug}', name: 'list')]
    public function list(Category $category): Response 
    {
        $product = $category->getProducts();
        return $this->render('pages/category/list.html.twig', compact('category', 'product'));
    }

    
}
