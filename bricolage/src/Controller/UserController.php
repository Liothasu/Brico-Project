<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;


class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

    public function someAction(): Response
    {

        $user = $this->getUser();
        $username = $user->getUserIdentifier(); 
        // $email = $user->getEmail();
        $roles = $user->getRoles();

        // ...

        return $this->render('user.html.twig', [
            'username' => $username,
            // 'email' => $email,
            'roles' => $roles,
        ]);
    }
}
