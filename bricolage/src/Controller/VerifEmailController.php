<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Require ROLE_ADMIN for *every* controller method in this class.
*
* @IsGranted("ROLE_ADMIN")
*/

class VerifEmailController extends AbstractController
{
    #[Route('/verif-email', name: 'app_verif_email')]
    public function verif(): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");

        /**@var User $user */
        $user = $this->getUser();

        return match ($user->isVerified()) {
            true => $this->render("main/home.html.twig"),
            false => $this->render("pages/verif-email/please-verify-email.html.twig"),
            
        };
        
    }
}
