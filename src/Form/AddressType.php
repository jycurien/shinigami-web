<?php

namespace App\Form;

use App\Dto\AddressDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address', TextType::class, [
                'label' => 'profile.edit.address',
                'row_attr' => [
                    'class' => 'form-row-with-error'
                ]
            ])
            ->add('zipCode', IntegerType::class, [
                'label' => 'profile.edit.zip_code',
                'row_attr' => [
                    'class' => 'form-row-with-error'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'profile.edit.city',
                'row_attr' => [
                    'class' => 'form-row-with-error'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AddressDto::class,
            'required' => false,
            'attr' => [
                'novalidate' => 'novalidate'
            ],
            'translation_domain' => 'user',
        ]);
    }
}