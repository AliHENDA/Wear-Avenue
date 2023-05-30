<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom associé à cette adresse",
                'attr' => [
                    'placeholder' => 'Quel nom souhaitez-vous donner à cette adresse?'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'firstname',
                'attr' => [
                    'placeholder' => 'Firstname'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Lastname',
                'attr' => [
                    'placeholder' => 'Lastname'
                ]
            ])
            ->add('company', TextType::class, [
                'label' => 'Company',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Company name (facultatif)'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Adress',
                'attr' => [
                    'placeholder' => "N° rue, nom de rue, complément d'adresse"
                ]
            ])
            ->add('postal', TextType::class, [
                'label' => 'Postal Code',
                'attr' => [
                    'placeholder' => 'Enter the postal code'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'attr' => [
                    'placeholder' => 'Enter the city'
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => 'Country',
                'attr' => [
                    'class' => 'ml-2'
                ]
            ])
            ->add('phone', TelType::class, [
                'label' => 'Phone number',
                'attr' => [
                    'placeholder' => 'Phone number'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Add an address',
                'attr' => [
                    'class' => "btn-block btn-info"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
