<?php


namespace App\Form;


use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class NewEmployeeType extends AbstractType
{
    // TODO
//    private $userRepository;
//
//    /**
//     * NewEmployeeType constructor.
//     * @param UserRepository $repository
//     */
//    public function __construct(UserRepository $repository)
//    {
//        $this->userRepository = $repository;
//    }
//
//    /**
//     * @param FormBuilderInterface $builder
//     * @param array $options
//     */
//    public function buildForm(FormBuilderInterface $builder, array $options)
//    {
//        $builder
//            ->add('roles', ChoiceType::class, [
//                'constraints' => new Assert\NotBlank(),
//                'choices'  => [
//                    'employee' => 'ROLE_STAFF',
//                    'administrator' => 'ROLE_ADMIN'
//                ],
//                'multiple' => true,
//                'label' => 'role'
//            ])
//            ->add('username', TextType::class, [
//                'constraints' => [
//                    new Assert\NotBlank(),
//                    new Callback([
//                        'callback' => [$this, 'isUsernameNotUnique'],
//                    ])
//                ],
//                'label' => 'profile.show.username'
//            ])
//            ->add('email', EmailType::class, [
//                'constraints' => [
//                    new Assert\Email(),
//                    new Callback([
//                        'callback' => [$this, 'isEmailNotUnique'],
//                    ])
//                ],
//                'label' => 'profile.show.email'
//            ])
//            ->add('contract', ContractType::class, [
//                'label' => 'contract.contract'
//            ])
//            ->add('firstName', TextType::class, [
//                'constraints' => new Assert\NotBlank(),
//                'label' => 'profile.edit.firstname'
//            ])
//            ->add('lastName', TextType::class, [
//                'constraints' => new Assert\NotBlank(),
//                'label' => 'profile.edit.lastname'
//            ])
//        ;
//    }
//
//    /**
//     * @param OptionsResolver $resolver
//     */
//    public function configureOptions(OptionsResolver $resolver)
//    {
//        $resolver->setDefaults([
//            'data_class' => User::class,
//            'translation_domain' => 'FOSUserBundle'
//        ]);
//    }
//
//    /**
//     * @param $data
//     * @param ExecutionContextInterface $context
//     * @throws \Doctrine\ORM\NonUniqueResultException
//     */
//    public function isUsernameNotUnique($data, ExecutionContextInterface $context)
//    {
//        if ($this->userRepository->isUsernameNotUnique($data)) {
//            $context->addViolation('fos_user.username.already_used');
//        }
//    }
//
//    /**
//     * @param $data
//     * @param ExecutionContextInterface $context
//     * @throws \Doctrine\ORM\NonUniqueResultException
//     */
//    public function isEmailNotUnique($data, ExecutionContextInterface $context)
//    {
//        if ($this->usersRepository->isEmailNotUnique($data)) {
//            $context->addViolation('fos_user.email.already_used');
//        }
//    }
}