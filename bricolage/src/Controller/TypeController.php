<?php

namespace App\Controller;

use App\Entity\Type;
use App\Repository\TypeRepository;
use App\Service\BlogService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TypeController extends AbstractController
{
    #[Route('/type/{slug}', name: 'type_show')]
    public function show(?Type $type, TypeRepository $typeRepository, BlogService $blogService): Response
    {
        if (!$type) {
            return $this->redirectToRoute('home');
        }

        $types = $typeRepository->findAll();

        return $this->render('pages/type/index.html.twig', [
            'entity' => $type,
            'types' => $types,
            'blogs' => $blogService->getPaginatedBlogs($type)
        ]);
    }
}
