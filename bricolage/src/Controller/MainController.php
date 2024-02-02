<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\PromoRepository;
use App\Repository\TypeRepository;
use App\Service\BlogService;
use App\Service\ConfigService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    public function __construct(private ConfigService $configService)
    {

    }

    #[Route('/', name: 'home')]
    public function home(CategoryRepository $categoryRepository, BlogService $blogService,TypeRepository $typeRepository, PromoRepository $promoRepository): Response
    {
        $currentPromos = $promoRepository->findCurrentPromotions();

        return $this->render('main/home.html.twig', [
            'categories' => $categoryRepository->findBy([], ['name' => 'asc']),
            'blogs' => $blogService->getPaginatedBlogs(),
            'types' => $typeRepository->findAllForWidget(),
            'currentPromos' => $currentPromos,
        ]);
    }
}
