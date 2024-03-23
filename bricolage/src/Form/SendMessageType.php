<?php

namespace App\Form;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SendMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('recipient', EntityType::class, [
            'class' => User::class,
            'choice_label' => 'username',
            'attr' => [
                'class' => 'form-control'
            ],
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->where('u.roles LIKE :roleUser')
                    ->orWhere('u.roles LIKE :roleHandyman')
                    ->andWhere('u.roles NOT LIKE :roleAdmin')
                    ->setParameter('roleUser', '%"ROLE_USER"%')
                    ->setParameter('roleHandyman', '%"ROLE_HANDYMAN"%')
                    ->setParameter('roleAdmin', '%"ROLE_ADMIN"%');
            }
        ])
        ->add('title', TextType::class, [
            'attr' => [
                'class' => 'form-control'
            ]
        ])
        ->add('content', TextareaType::class, [
            'attr' => [
                'class' => 'form-control'
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
