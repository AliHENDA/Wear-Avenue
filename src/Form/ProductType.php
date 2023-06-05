<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\InventoryType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name'
            ])
            ->add('description', TextareaType::class)
            ->add('picture', UrlType::class, [
                'label' => 'Picture',

            ])
            ->add('gender', ChoiceType::class, [
                'choices'  => [
                    'Woman' => 'Woman',
                    'Man' => 'Man',
                    'Unisex' => 'Unisex'
                ],
                // Boutons radios
                'expanded' => true, 
                'label_attr' => [
                    'class' => 'radio-inline',
                ],         
                'label' => 'Gender'     
            ])
            ->add('color')
            ->add('rate')
            ->add('price', MoneyType::class, [
                'label' => 'Price'
            ])
            ->add('best_sellers_order', IntegerType::class,[
                'required' => false
            ])
            ->add('category', EntityType::class, [
                'label' => 'Category',
                'class' => Category::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
                'choice_label' => 'name',
                //'multiple' => true,
                //'expanded' => true,
            ])
            ->add('inventories', CollectionType::class, [
                'entry_type' => InventoryType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'Inventaire'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
