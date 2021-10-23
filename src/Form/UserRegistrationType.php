<?php

namespace App\Form;

use App\Dto\UserRegistrationDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
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
                    'class' => 'form-row-with-error'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'form.email',
                'row_attr' => [
                    'class' => 'form-row-with-error'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'form.repeat_password_error',
                'options' => ['row_attr' => ['class' => 'form-row-with-error']],
                'required' => true,
                'first_options'  => ['label' => 'form.password'],
                'second_options' => ['label' => 'form.password_confirmation'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserRegistrationDto::class,
            'required' => false,
            'attr' => [
                'novalidate' => 'novalidate'
            ],
            'translation_domain' => 'user',
        ]);
    }
}
