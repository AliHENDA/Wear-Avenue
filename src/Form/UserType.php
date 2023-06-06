<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class)
            ->add('roles', ChoiceType::class, [

                'label' => 'Role',
                'choices'  => [
                    'User' => 'ROLE_USER',
                    'Manager' => 'ROLE_MANAGER',
                    'Admin' => 'ROLE_ADMIN',
                ],
                // Choix multiple => Tableau 
                'multiple' => true,
                // On veut des checkboxes 
                'expanded' => true,
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Firstname',
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Name',
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
                // ici on recupere le form depuis l'event (car on va bosser avec)
                $form = $event->getForm();
                // ici on recupere le user mappé sur le form depuis l'event 
                $user = $event->getData();
                if ($user->getId() !== NULL) {
                    // Edit
                    $form->add('password', PasswordType::class, [
                        'mapped' => false,
                        'attr' => [
                            'placeholder' => 'Let empty if unchanged'
                        ],
                        ]);
                } else {
                    // New
                    $form->add('password', null, [
                        'empty_data' => '',
                        'help' => 'Make sure it\'s at least 8 characters including a number and a lowercase letter and a special character.',
                        // On déplace les contraintes de l'entité vers le form d'ajout
                        'constraints' => [
                            new NotBlank(),
                            new Regex(
                                "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/"
                            ),
                        ],
                    ]);
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
