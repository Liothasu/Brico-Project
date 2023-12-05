<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        // Vous pouvez ajouter ici le code pour charger des données pour votre page d'accueil
        // ...

        return $this->render('main/home.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    #[Route('/contact', name: 'contact')]
    public function showContactForm(): Response
    {
        return $this->render('contact/index.twig');
    }
}