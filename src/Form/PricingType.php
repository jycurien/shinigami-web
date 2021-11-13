<?php

namespace App\Form;

use App\Dto\PricingDto;
use App\Event\Subscriber\PricingTypeConditionnalFieldsSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PricingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount', NumberType::class, [
                'label' => 'amount',
                'attr' => [
                    'step' => 0.01,
                    'min' => 0,
                    'max' => 10000
                ]
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'description'
            ])
            ->addEventSubscriber(new PricingTypeConditionnalFieldsSubscriber());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PricingDto::class,
            'translation_domain' => 'form'
        ]);
    }
}
