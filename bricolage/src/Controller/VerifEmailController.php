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
    #[Route('/verif/email', name: 'app_verif_email')]
    public function verif(): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");

        /** @var User $user */
        $user = $this->getUser();

        if ($user->isVerified()) {
            // Redirect to home or any other route upon successful email verification
            return $this->redirectToRoute('home');
        }

        // If not verified, render the verify_email template
        return $this->render("pages/registration/verify_email.html.twig");
    }

}
