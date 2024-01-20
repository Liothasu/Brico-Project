<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Require ROLE_ROLE for *every* controller method in this class.
 *
 * @IsGranted("ROLE_USER")
 */

#[Route('/profile', name: 'profile_')]
class UserController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('pages/user/index.html.twig');
    }

    #[Route('/edit', name: 'edit')]
    public function editProfile(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $referer = $request->headers->get('referer');

        $user = $this->getUser();
        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Username is required.']),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Username must be at least {{ limit }} characters.',
                        'max' => 30,
                        'maxMessage' => 'Username must not exceed {{ limit }} characters.',
                    ]),
                    new Regex([
                        'pattern' => '~^[a-zA-Z0-9_.-]+$~',
                        'message' => 'Username should only contain alphanumeric characters, ".", "-", and "_".',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Missing email address.']),
                    new Email(['message' => 'Invalid email address.']),
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('currentPassword', PasswordType::class, [
                'label' => 'Confirm your current password',
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Please enter your current password.']),
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            // ->add('newPassword', RepeatedType::class, [
            //     'type' => PasswordType::class,
            //     'first_options' => [
            //         'label' => 'New Password',
            //         'attr' => [
            //             'class' => 'form-control',
            //         ],
            //     ],
            //     'second_options' => [
            //         'label' => 'Confirm Password',
            //         'attr' => [
            //             'class' => 'form-control',
            //         ],
            //     ],
            //     'invalid_message' => 'The password fields must match.',
            //     'mapped' => false,
            //     'constraints' => [
            //         new NotBlank([
            //             'message' => 'Please enter a password',
            //         ]),
            //         new Length([
            //             'min' => 6,
            //             'minMessage' => 'Your password should be at least {{ limit }} characters',
            //             // max length allowed by Symfony for security reasons
            //             'max' => 4096,
            //         ]),
            //     ],
            // ])
            ->add('phoneNumber', TelType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('numStreet', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('city', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('zipCode', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ]
            ])
            ->getForm();

            if ($request->isMethod('POST')) {
                $form->handleRequest($request);
            
                if ($form->isSubmitted() && $form->isValid()) {
                    // $hashedPassword = $userPasswordHasher->hashPassword($user, $form->get('newPassword')->getData());
                    // $user->setPassword($hashedPassword);
            
                    $entityManager->persist($user);
                    $entityManager->flush();
            
                    $this->addFlash('success', 'Profile updated successfully.');
            
                    return $this->redirectToRoute('profile_index');
                }
            }

        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
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
