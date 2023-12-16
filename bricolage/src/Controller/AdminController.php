<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");

        /**@var User $user */
        $user = $this->getUser();

        return match ($user->isVerified()) {
            true => $this->render("pages/main/home.html.twig"),
            false => $this->render("pages/admin/please-verify-email.html.twig"),
            
        };
        
    }

    #[Route('/admin/user', name: 'admin_user_')]
    public function adminUser(): Response 
    {
        return $this->render('pages/admin/user/list.html.twig');
        
    }

}
