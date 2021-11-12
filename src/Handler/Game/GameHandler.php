<?php


namespace App\Handler\Game;


use App\Entity\Room;
use App\Factory\GameFactory;
use App\Handler\User\UserHandler;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class GameHandler
{
    /**
     * @var UserHandler
     */
    private $userHandler;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;
    /**
     * @var GameFactory
     */
    private $gameFactory;
    /**
     * @var UserPlayGamesHandler
     */
    private $userPlayGamesHandler;

    public function __construct(UserHandler $userHandler, FlashBagInterface $flashBag, GameFactory $gameFactory, UserPlayGamesHandler $userPlayGamesHandler)
    {
        $this->userHandler = $userHandler;
        $this->flashBag = $flashBag;
        $this->gameFactory = $gameFactory;
        $this->userPlayGamesHandler = $userPlayGamesHandler;
    }

    /**
     * @param array $data
     * @param string $action
     * @param null $game
     * @return bool
     */
    public function handle(array $data, string $action = 'create', $game = null): bool
    {
        // we need at least one player to create a game
        if("" != $data['player_hidden']) {

            $teams = $this->getTeams($data['team']['hidden']);

            // We generate all the Users With the string of player
            $usersWithTeam = $this->userHandler->getUsersWithPlayerString($data['player_hidden'], $teams);

            // Normally the capacity is check in JS but need to sure the employee
            if($this->capacityIsCorrect($usersWithTeam, $data['room'])) {

                // we create an object Game, if Edit/Update we don't create a Game object
                if('create' == $action) {
                    $game = $this->gameFactory->create($data);
                } else {
                    $game = $this->gameFactory->update($game, $data);
                }

                // We create the UserPlayGame for every Users
                $this->userPlayGamesHandler->createUsersPlayGames($usersWithTeam, $game);

                return true;
            }

            $this->flashBag->add('error', 'Capacité incorrecte');
            return false;
        }
        $this->flashBag->add('error', 'game.create.error.no_player');
        return false;
    }

    /**
     * @param $teamString
     * @return array
     */
    private function getTeams(string $teamString): array
    {
        // we get rid of the ";" at the end of the string
        if(substr($teamString, -1) === ";") {
            $teamString = substr_replace($teamString, "", -1);
        }

        return explode(';', $teamString);
    }

    /**
     * @param User[] $users
     * @param Room $room
     * @return bool
     */
    public function capacityIsCorrect(array $users, Room $room): bool
    {
        if(count($users) > $room->getCapacity()) {
            $this->flashBag->add('error', 'game.create.error.capacity');
            return false;
        }

        return true;
    }
}