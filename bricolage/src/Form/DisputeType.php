<?php

namespace App\Form;

use App\Entity\Blog;
use App\Entity\Comment;
use App\Entity\Dispute;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DisputeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',  null, [
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('problemType', ChoiceType::class, [
                'choices' => [
                    'Blog' => 'Blog',
                    'Project' => 'Project',
                    'Comment' => 'Comment',
                    'Order' => 'Order',
                ],
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
            ])
            ->add('blog', EntityType::class, [
                'class' => Blog::class,
                'choice_label' => 'title',
                'data' => $options['blogs'] ?? null,
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
            ])
            ->add('comment', EntityType::class, [
                'class' => Comment::class,
                'choice_label' => 'content',
                'data' => $options['comments'] ?? null,
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
            ]);

        if (!empty($options['projects'])) {
            $builder->add('project', null, [
                'choices' => $options['projects'],
                'required' => false,
                'attr' => [
                    'class' => 'form-control mb-3',
                ],
            ]);
        }

        if (!empty($options['orders'])) {
            $builder->add('order', null, [
                'choices' => $options['orders'],
                'required' => false,
                'attr' => [
                    'class' => 'form-control mb-3',
                ],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dispute::class,
            'blogs' => null,
            'projects' => null,
            'comments' => null,
            'orders' => null,
        ]);
    }
}
