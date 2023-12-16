<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(CategoryRepository $categoryRepository): Response
    {
        return $this->render('main/home.twig', [
            'categories' => $categoryRepository->findBy([],
            ['categoryOrder' => 'asc'])
        ]);
    }
} 