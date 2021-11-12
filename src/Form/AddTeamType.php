<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddTeamType extends  AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // if Editing we get the existing teams
        $teams = ($options['data']['teams']) ? : '';

        $builder
            ->add('hidden', HiddenType::class, [
                'attr' => ['disabled' => false],
                'data' => $teams,
            ])
            ->add('text', TextType::class, [
                'required' => false,
                'label' => false,
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'translation_domain' => 'form'
        ]);
    }
}