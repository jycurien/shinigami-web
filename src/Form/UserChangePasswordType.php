<?php

namespace App\Form;

use App\Dto\UserChangePasswordDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['require_old_password']) {
            $builder
                ->add('oldPassword', PasswordType::class, [
                    'label' => 'form.current_password',
                ])
            ;
        }

        $builder
            ->add('newPassword', RepeatedType::class, [
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
            'data_class' => UserChangePasswordDto::class,
            'require_old_password' => false,
            'required' => false,
            'attr' => [
                'novalidate' => 'novalidate'
            ],
            'translation_domain' => 'user',
        ]);
        $resolver->setAllowedTypes('require_old_password', 'bool');
    }
}
