<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];
        $builder
            ->add('addresses', EntityType::class, [
                'class'=> Address::class,
                'required' => true,
                'label' => 'Choose your delivery address',
                'choices' => $user->getAddresses(),
                'multiple' => false,
                'expanded' => true
            ])
            ->add('carriers', EntityType::class, [
                'class'=> Carrier::class,
                'required' => true,
                'label' => 'Choose your carrier',
                'multiple' => false,
                'expanded' => true
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Purshase Now",
                'attr' => [
                    'class' => 'btn'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user' => array()
        ]);
    }
}
