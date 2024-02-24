<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/profile', name: 'profile_')]
class UserController extends AbstractController
{
    private UserPasswordHasherInterface $passwordHasher;
    private EntityManagerInterface $entityManager;

    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager)
    {
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('pages/user/index.html.twig');
    }

    #[Route('/edit', name: 'edit')]
    public function edit(Request $request): Response
    {
        $referer = $request->headers->get('referer');

        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('newPassword')->getData();
            if ($newPassword) {
                $user->setPassword($this->passwordHasher->hashPassword($user, $newPassword));
            }

            $this->entityManager->flush();

            $this->addFlash('success', 'Your profile has been updated successfully.');

            return $this->redirectToRoute('profile_index');
        }

        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView(),
            'referer' => $referer,
        ]);
    }

    #[Route('/show/{username}', name: 'show')]
    public function show(User $user, Request $request): Response
    {
        $referer = $request->headers->get('referer');

        return $this->render('pages/user/show.html.twig', [
            'user' => $user,
            'referer' => $referer,
        ]);
    }
}
