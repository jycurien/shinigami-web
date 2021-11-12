<?php


namespace App\Handler\Game;


use App\Entity\Game;
use App\Entity\UserPlayGame;
use App\Factory\UserPlayGameFactory;
use App\Service\GamePriceCalculator;
use Doctrine\ORM\EntityManagerInterface;

class UserPlayGamesHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var GamePriceCalculator
     */
    private $priceCalculator;
    /**
     * @var UserPlayGameFactory
     */
    private $userPlayGameFactory;

    public function __construct(EntityManagerInterface $entityManager, GamePriceCalculator $priceCalculator, UserPlayGameFactory $userPlayGameFactory)
    {
        $this->entityManager = $entityManager;
        $this->priceCalculator = $priceCalculator;
        $this->userPlayGameFactory = $userPlayGameFactory;
    }

    /**
     * Create the UserPlayGames and persist them to database with the Game
     * @param array $usersWithTeam
     * @param Game $game
     */
    public function createUsersPlayGames(array $usersWithTeam, Game $game): void
    {
        // if Edit form, we remove all userPlayGame of the existing game
        foreach ($game->getUserPlayGames() as $userPlayGame) {
            $game->removeUserPlayGame($userPlayGame);
        }

        foreach ($usersWithTeam as $userWithTeam) {
            $userPlayGame = $this->userPlayGameFactory->create($userWithTeam['user'], $game);
            $userPlayGame->setTeam($userWithTeam['team']);
            $game->addUserPlayGame($userPlayGame);
            $this->entityManager->persist($userPlayGame);
        }

        // Calculate the price
        // Loop on all UserPlayGames only after they were all created, because we need the total number of UserPlayGames to check for group pricing
        foreach ($game->getUserPlayGames() as $userPlayGame) {
            $userPlayGame->setPrice($this->priceCalculator->pricePerPlayer($userPlayGame));
        }

        // We also put the game in the database
        $this->entityManager->persist($game);
        $this->entityManager->flush();
    }

    /**
     * Concat all User ids with their team id in one string
     * @param UserPlayGame[] $usersPlayGames
     * @return string
     */
    public function formatUsersWithTeamsInString($usersPlayGames): string
    {
        $playersString = '';

        // we get all the teams needed in the game
        $teams = $this->getGameTeams($usersPlayGames);

        // we concat the Users IDs
        foreach ($usersPlayGames as $userPlayGame) {
            // we get the index of the user team in teams
            $index = array_search($userPlayGame->getTeam(), $teams);
            $playersString .= $userPlayGame->getUser()->getId().'-'. $index.';';
        }

        return $playersString;
    }

    /**
     * Get all the teams for $usersPlayGames
     * @param UserPlayGame[] $usersPlayGames
     * @return array
     */
    private function getGameTeams($usersPlayGames): array
    {
        $teams = [];

        // We always initialise the array with Solo value
        $teams[0] = 'solo';

        // Add teams to the $teams array
        foreach ($usersPlayGames as $userPlayGame) {
            if(!in_array($userPlayGame->getTeam(), $teams)) {
                $teams[] = $userPlayGame->getTeam();
            }
        }

        return $teams;
    }

    /**
     * Concat all the teams for $usersPlayGames in one string
     * @param UserPlayGame[] $usersPlayGames
     * @return string
     */
    public function formatTeamsInString($usersPlayGames): string
    {
        return implode(";", $this->getGameTeams($usersPlayGames));
    }
}