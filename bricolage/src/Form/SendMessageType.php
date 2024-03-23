<?php

namespace App\Form;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SendMessageType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

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
                    $currentUser = $this->security->getUser();

                    return $er->createQueryBuilder('u')
                        ->where('u.roles NOT LIKE :roleAdmin')
                        ->andWhere('u != :currentUser')
                        ->setParameter('roleAdmin', '%"ROLE_ADMIN"%')
                        ->setParameter('currentUser', $currentUser);
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
