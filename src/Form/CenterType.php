<?php


namespace App\Form;


use App\Entity\Center;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CenterType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'constraints' => new Assert\NotBlank(['message' => 'center.select']),
            'class' => Center::class,
            'label' => 'center.list',
            'choice_label' => function ($center) {
                return $center->getAddress()->getZipCode() .' - '.$center->getName(). ' ('.$center->getCode().')';
            },
            'translation_domain' => 'form'
        ]);
    }

    /**
     * @return null|string
     */
    public function getParent(): ?string
    {
        return EntityType::class;
    }
}