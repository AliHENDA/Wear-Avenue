<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'disabled' => true,
            ])
            ->add('firstname', TextType::class, [
                'label' => 'firstname',
                'disabled' => true,
            ])
            ->add('lastname', TextType::class, [
                'label' => 'lastname',
                'disabled' => true,
            ])
            ->add('old_password', PasswordType::class, [
                'label' => "My current password",
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Please enter your current password'
                ]
            ])
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Passwords do not match',
                'first_options'  => [
                    'label' => 'My new password',
                    'attr' => [
                        'placeholder' => 'Please enter your new password'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirm your new password',
                    'attr' => [
                    'placeholder' => 'Please enter your new password'
                    ]
                ],
                'required' => true,
                'mapped' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Update"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

