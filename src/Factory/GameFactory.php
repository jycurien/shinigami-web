<?php


namespace App\Factory;


use App\Entity\Game;

class GameFactory
{
    /**
     * @param array $data
     * @return Game
     */
    public function create(array $data): Game
    {
        $game = New Game();
        $game->setRoom($data['room']);
        $game->setDate($data['date']);

        return $game;
    }

    /**
     * @param Game $game
     * @param array $data
     * @return Game
     */
    public function update(Game $game, array $data): Game
    {
        $game->setRoom($data['room']);
        $game->setDate($data['date']);

        return $game;
    }
}