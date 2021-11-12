<?php


namespace App\Handler\User;


use App\Entity\User;
use App\Repository\UserRepository;
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
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(EntityManagerInterface $entityManager, FlashBagInterface $flashBag, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->flashBag = $flashBag;
        $this->userRepository = $userRepository;
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

    /**
     * Return users with their selected team
     * @param string|null $playerString
     * @param array $teams
     * @return array
     */
    public function getUsersWithPlayerString(?string $playerString, array $teams): array
    {
        if(null == $playerString) {
            return [];
        }

        // we get rid of the ";" at the end of the string
        if(substr($playerString, -1) === ";") {
            $playerString = substr_replace($playerString, "", -1);
        }

        $playersArray = explode(';', $playerString);

        $usersAndTeam = [];

        foreach ($playersArray as $playerAndTeam) {
            $explode = explode("-", $playerAndTeam);
            $user = $explode[0];
            $team = $explode[1];
            $usersAndTeam[] = [
                'user' => $this->userRepository->find($user),
                'team' => $teams[$team]
            ];
        }

        return $usersAndTeam;
    }
}