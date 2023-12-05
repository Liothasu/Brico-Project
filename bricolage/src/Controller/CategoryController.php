<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'category_form')]
    public function category_form(): Response
    {
        $form = $this->createForm(CategoryType::class);
        return $this->render('category/category_form.twig', [   
            'form' => $form->createView()
        ]);
    }

    public function CategoryCreate(): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        return $this->render('category/category_form.twig', [
            'form' => $form->createView()
        ]);
    }

    public function CategorySave(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            dump($category);die;
        }
        return $this->render('category/category_form.twig', [
            'form' => $form->createView()
        ]);
    }

    public function CategorySaveOnBD(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($category);
            $entityManager->flush();
        }
        return $this->render('category/category_form.twig', [
            'form' => $form->createView()
        ]);
    }
}
