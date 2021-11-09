<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CardNumberType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $label = isset($options["data"]["label"]) ? $options["data"]["label"] : 'card_number';
        $labelSubmit = isset($options["data"]["labelSubmit"]) ? $options["data"]["labelSubmit"] : 'search';

        $builder
            ->add('number', NumberType::class, [
                'required'  => true,
                'label'     => $label,
                'attr'      => [
                    'placeholder' => 'card_number'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => $labelSubmit
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => null,
                'translation_domain' => 'form'
            ]);
    }
}