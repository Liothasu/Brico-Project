<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('paymentMode', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Paypal' => 'paypal',
                    'Master Card' => 'master_card',
                    'Visa' => 'visa',
                    'Maestro' => 'maestro',
                ],
                'attr' => [
                    'class' => 'form-select'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
