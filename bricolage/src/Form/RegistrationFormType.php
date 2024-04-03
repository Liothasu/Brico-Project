<?php

namespace App\Form;

use App\Entity\User;
use App\Validator\Constraints\NotContainsAdmin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'ToolCraftJax',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9_]+$/',
                        'message' => 'Your username should contain only letters, numbers and underscores.',
                    ]),
                    new NotContainsAdmin(['message' => 'This username cannot be used.']),
                ],
            ])
            ->add('firstName', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Jack',
                ],
            ])
            ->add('lastName', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Carpenter',
                ],
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'example@gmail.com',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                        'message' => 'Please enter a valid email address.',
                    ])
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Password',
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Enter your password',
                    ],
                ],
                'second_options' => [
                    'label' => 'Repeat Password',
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ],
                'invalid_message' => 'The password fields must match.',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$/',
                        'message' => 'Your password must contain at least one uppercase letter, one lowercase letter, one digit, and one special character.',
                    ]),
                ],
            ])
            ->add('phoneNumber', TelType::class, [
                'label' => 'Phone number',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '04 77 77 77 77',
                ],
                'constraints' => [
                    new Length([
                        'max' => 10,
                        'maxMessage' => 'Your phone number should have at most {{ limit }} characters.',
                    ]),
                ],
            ])
            ->add('numStreet', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Avenue Huart Hamoir 106',
                ],
                'label' => 'Address (including street number)',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z\s]+\s+\d+$/',
                        'message' => 'Please enter a valid address format.',
                    ]),
                ],
            ])
            ->add('city', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Schaerbeek',
                ],
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 100,
                        'minMessage' => 'Your city should have at least {{ limit }} characters.',
                        'maxMessage' => 'Your city should have at most {{ limit }} characters.',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z\s]+$/',
                        'message' => 'Your city should contain only letters and spaces.',
                    ]),
                ],
            ])
            ->add('zipCode', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '1030',
                ],
                'label' => 'Zip Code',
                'constraints' => [
                    new Length([
                        'min' => 4,
                        'max' => 10,
                        'minMessage' => 'Your zip code should have at least {{ limit }} characters.',
                        'maxMessage' => 'Your zip code should have at most {{ limit }} characters.',
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
