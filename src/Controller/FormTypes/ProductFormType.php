<?php
namespace App\Controller\FormTypes;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Product Title',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Product Description',
                'required' => true,
            ])
            ->add('price', NumberType::class, [
                'label' => 'Product Price',
                'scale' => 2,
            ])
            ->add('stock', NumberType::class, [
                'label' => 'Product Stock',
            ])
            ->add('active', CheckboxType::class, [
                'label' => 'Active',
                'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save Product',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
