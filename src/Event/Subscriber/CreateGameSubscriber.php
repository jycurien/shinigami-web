<?php


namespace App\Event\Subscriber;


use App\Entity\Center;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints as Assert;

class CreateGameSubscriber implements  EventSubscriberInterface
{
    /**
     * @var Security
     */
    private $security;

    /**
     * CreateGameListener constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {

        $this->security = $security;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        Return [
            FormEvents::PRE_SET_DATA => 'onPreSetData',
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function onPreSetData(FormEvent $event)
    {

        if ($this->security->isGranted('ROLE_ADMIN')) {

            $event->getForm()
                ->add('center', EntityType::class, [
                    'constraints' => new Assert\NotBlank(['message' => 'Veuillez sélectionner un centre']),
                    'class' => Center::class,
                    'label' => 'center.list',
                    'placeholder' => 'center.select',
                    'choice_label' => function ($center) {
                        return $center->getAddress()->getZipCode() .' - '.$center->getName(). ' ('.$center->getCode().')';
                    },
                    'choice_attr' => function($center, $key, $value) {
                        return ['data-center-id' => $value];
                    },
                ] );

        } elseif ($this->security->isGranted('ROLE_STAFF')) {

            // We get the employee's center
            $center = $this->security->getUser()->getContract()->getCenter();

            $event->getForm()
                ->add('center', TextType::class, [
                    'disabled' => true,
                    'label' => 'center.employee',
                    'data' => $center->getAddress()->getZipCode() .' - '.$center->getName(). ' ('.$center->getCode().')',
                    'attr' => array('data-center-id' => $center->getId()),
                ] );
        }
    }
}