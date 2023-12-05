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

     // Accéder à l'utilisateur actuel
        $user = $this->getUser();

        // Vous pouvez maintenant accéder aux propriétés de l'utilisateur
        $username = $user->getUserIdentifier(); // Utilisez getUserIdentifier à la place de getUsername
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
