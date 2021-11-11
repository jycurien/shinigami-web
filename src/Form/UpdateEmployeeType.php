<?php


namespace App\Form;


use App\Dto\AddressDto;
use App\Dto\EmployeeDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateEmployeeType extends  AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('phoneNumber', TextType::class, [
                'label' => 'profile.edit.phone_number'
            ])
            ->add('birthDate', BirthdayType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'label' => 'profile.edit.birth_date'
            ])
            ->add('image', FileType::class, [
                'label' => 'profile.edit.image',
                'data_class' => null,
                'row_attr' => [
                    'class' => 'js-draggable-image',
                    'data-initial-image' => $options['picture_url']
                ]
            ])
            ->add('address', AddressType::class, [
                'label' => false,
                'data_class' => AddressDto::class,
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EmployeeDto::class,
            'translation_domain' => 'user',
            'picture_url' => null,
        ]);
    }

    /**
     * @return null|string
     */
    public function getParent(): ?string
    {
        return 'App\Form\NewEmployeeType';
    }
}