<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CardOrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('center', CenterType::class)
            ->add('quantity', IntegerType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez indiquer la quantité de cartes']),
                    new Assert\Range([
                        'min' => 100,
                        'max' => 10000,
                        'minMessage' => 'Quantité minimum : {{ limit }} cartes',
                        'maxMessage' => 'Quantité maximum : {{ limit }} cartes',
                    ])
                ],
                'label' => 'card_quantity',
                'attr' => [
                    'step' => 100,
                    'min' => 100,
                    'max' => 10000
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'order',
                'attr' => ['class' => 'btn btn-lg btn-primary']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'translation_domain' => 'form'
        ]);
    }
}
