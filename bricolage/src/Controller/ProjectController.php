<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ProjectController extends AbstractController
{
    #[Route('/project', name: 'project')]
    public function project(): Response
    {
        return $this->render('project/project.twig');
    }
}
