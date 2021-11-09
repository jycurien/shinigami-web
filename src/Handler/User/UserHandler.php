<?php


namespace App\Handler\User;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class UserHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    public function __construct(EntityManagerInterface $entityManager, FlashBagInterface $flashBag)
    {
        $this->entityManager = $entityManager;
        $this->flashBag = $flashBag;
    }

    /**
     * @param User $user
     * @param string $cardNumber
     */
    public function updateCardNumbersUser(User $user, string $cardNumber): void
    {
        // we check if the card number is already in the field CardNumbers
        if(!in_array($cardNumber, $user->getCardNumbers())) {
            $user->addCardNumber($cardNumber);
            $this->entityManager->flush();
            $this->flashBag->add('success','card.activate.ok');
        } else {
            $this->flashBag->add('notice','card.activate.already_activated');
        }
    }
}