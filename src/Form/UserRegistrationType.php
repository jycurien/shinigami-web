<?php

namespace App\Form;

use App\Dto\UserDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'form.username',
                'row_attr' => [
                    'class' => 'mb-4'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'form.email',
                'row_attr' => [
                    'class' => 'mb-4'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserDto::class,
            'required' => false,
            'attr' => [
                'novalidate' => 'novalidate'
            ],
            'translation_domain' => 'user',
        ]);
    }
}
