<?php

namespace App\Form;

use App\Entity\Blog;
use App\Entity\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

//Not used
class BlogEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'attr' => [
                    'class' => 'form-control']
            ])
            ->add('featuredText', TextType::class, [
                'attr' => [
                    'class' => 'form-control']
                    ])
            ->add('content', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control']
            ])
            ->add('types', EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Edit blog',
                'attr' => [
                    'class' => 'btn btn-primary mt-4']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Blog::class,
        ]);
    }
}
