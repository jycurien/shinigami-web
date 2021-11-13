<?php


namespace App\Event\Subscriber;


use App\Dto\PricingDto;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class PricingTypeConditionnalFieldsSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData'
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event): void
    {
        /** @var PricingDto $pricingDto */
        $pricingDto = $event->getData();
        $form = $event->getForm();

        if (null !== $pricingDto->numberOfGames) {
            $form->add('numberOfGames', IntegerType::class, [
                'required' => false,
                'label' => 'number_of_games_fidelity_pricing'
            ]);
        }

        if (null !== $pricingDto->numberOfPlayers) {
            $form->add('numberOfPlayers', IntegerType::class, [
                'required' => false,
                'label' => 'number_of_players_group_pricing'
            ]);
        }
    }
}