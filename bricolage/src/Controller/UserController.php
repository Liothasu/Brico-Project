<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class UserController extends AbstractController
{
    // private EmailVerifier $emailVerifier;
    
    // public function __construct(EmailVerifier $emailVerifier)
    // {
    //     $this->emailVerifier = $emailVerifier;
    // }

    #[Route('/user/{username}', name: 'user')]

    public function index(User $user): Response
    {
        return $this->render('pages/user/index.html.twig', [
            'user' => $user,
        ]);
    }


    // #[Route('/register', name: 'app_register')]
    // public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    // {
    //     $user = new User();
    //     $form = $this->createForm(RegistrationFormType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $user->setPassword(
    //             $userPasswordHasher->hashPassword(
    //                 $user,
    //                 $form->get('plainPassword')->getData()
    //             )
    //         );

    //         $entityManager->persist($user);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('home');

    //         // generate a signed url and email it to the user
    //         $this->emailVerifier->sendEmailConfirmation('app_verify_email_user', $user,
    //             (new TemplatedEmail())
    //                 ->from(new Address('admin@hardware-store.com', 'Mail HSSecurity'))
    //                 ->to($user->getEmail())
    //                 ->subject('Please Confirm your Email')
    //                 ->htmlTemplate('pages/registration/confirmation_email.html.twig')
    //         );

    //         return $userAuthenticator->authenticateUser(
    //             $user,
    //             $authenticator,
    //             $request
    //         );
    //     }

    //     return $this->render('pages/registration/register.html.twig', [
    //         'registrationForm' => $form->createView(),
    //     ]);
    // }

    // #[Route('/verify/email', name: 'app_verify_email_user')]
    // public function verifyUserEmail(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

    //     try {
    //         $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
    //     } catch (VerifyEmailExceptionInterface $exception) {
    //         $this->addFlash('verify_email_error', $exception->getReason());
    
    //         return $this->redirectToRoute('app_register');
    //     }
    
    //     $this->addFlash('success', 'Your email address has been verified.');
    
    //     return $this->redirectToRoute('app_verif_email');
    // }
}
