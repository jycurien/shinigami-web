<?php

namespace App\Form;

use App\Dto\UserEditDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'profile.edit.firstname',
                'row_attr' => [
                    'class' => 'form-row-with-error'
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'profile.edit.lastname',
                'row_attr' => [
                    'class' => 'form-row-with-error'
                ]
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'profile.edit.phone_number',
                'row_attr' => [
                    'class' => 'form-row-with-error'
                ]
            ])
            ->add('birthDate', BirthdayType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'label' => 'profile.edit.birth_date',
                'row_attr' => [
                    'class' => 'form-row-with-error'
                ]
            ])
            ->add('image', FileType::class, [
                'label' => 'profile.edit.image',
                'data_class' => null,
                'row_attr' => [
                    'class' => 'form-row-with-error'
                ]
            ])
            ->add('address', AddressType::class, [
                'label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserEditDto::class,
            'required' => false,
            'attr' => [
                'novalidate' => 'novalidate'
            ],
            'translation_domain' => 'user',
        ]);
    }
}
