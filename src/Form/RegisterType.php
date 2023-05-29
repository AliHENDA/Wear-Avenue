<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'attr' => [
                    'placeholder' => 'Your firstname'
                ]
            ])
            ->add('lastname', TextType::class, [
                'attr' => [
                    'placeholder' => 'Your lastname'
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'Your email'
                ]
            ])
            //->add('roles', ChoiceType::class, [
            //    'choices' =>
            //])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => "Passwords don't match",
                'first_options'  => [
                    'attr' => [
                        'placeholder' => 'Please enter your password'
                    ]
                ],
                'second_options' => [
                    'label' => 'Password confirmation',
                    'attr' => [
                    'placeholder' => 'Please enter your password'
                    ]
                ],
                'required' => true,
                
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Sign up"
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