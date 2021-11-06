<?php


namespace App\Form;


use App\Dto\ArticleDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $pictureOptions = [
            'required' => true,
            'label' => false,
            'data_class' => null,
            'row_attr' => [
                'class' => 'js-draggable-image'
            ]
        ];

        if (null === $options['picture_url']) {
            $pictureOptions['constraints'] = [
                new Assert\NotBlank([
                    'message' => 'Veuillez importer une image',
                ])
            ];
        }

        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label' => "article.title",
                'attr' => [
                    'placeholder' => "article.title"
                ]
            ])
            ->add('content', TextareaType::class, [
                'attr' => ['class' => 'ckeditor'],
                'required' => true,
                'label' => false,
                'constraints' => [
                    new Callback([
                        'callback' => [$this, 'isNotEmpty'],
                    ])
                ]
            ])
            ->add('picture', FileType::class, $pictureOptions)
            ->add('slider', CheckboxType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'form-switch'
                ],
                'attr' => [
                    'data-toggle' => 'toggle',
                    'data-on' => 'Oui',
                    'data-off' => 'Non'
                ]
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ArticleDto::class,
            'picture_url' => null,
            'translation_domain' => 'form'
        ]);
    }

    /**
     * @param $data
     * @param ExecutionContextInterface $context
     */
    public function isNotEmpty($data, ExecutionContextInterface $context)
    {
        $content = trim(strip_tags($data), "&nbsp;");
        if (empty($content)) {
            $context->addViolation('Veuillez saisir un contenu');
        }
    }
}