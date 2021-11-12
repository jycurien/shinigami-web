<?php


namespace App\Factory;


use App\Entity\Game;
use App\Entity\User;
use App\Entity\UserPlayGame;

class UserPlayGameFactory
{
    public function create(User $user, Game $game): UserPlayGame
    {
        $userPlayGame = New UserPlayGame();
        $userPlayGame->setGame($game);
        $userPlayGame->setUser($user);

        return $userPlayGame;
    }
}