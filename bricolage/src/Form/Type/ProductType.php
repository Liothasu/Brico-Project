<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Promo;
use App\Entity\Supplier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reference')
            ->add('nameProduct')
            ->add('color')
            ->add('designation')
            ->add('quantity')
            ->add('unitPrice')
            ->add('priceVAT')
            ->add('vat')
            ->add('promos', EntityType::class, [
                'class' => Promo::class, 'choice_label' => 'id', 'multiple' => true,
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class, 'choice_label' => 'id',
            ])
            ->add('supplier', EntityType::class, [
                'class' => Supplier::class, 'choice_label' => 'id',
            ])

            // ->add('save', SubmitType::class)

            // if($form->get('save’)->isClicked()){
                
            // }
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
