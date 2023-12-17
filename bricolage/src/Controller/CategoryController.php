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
        $categoryRepository = $entityManager->getRepository(Category::class);
        $cat = $categoryRepository->findAll();

        return $this->render('pages/category/form.html.twig', [
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
