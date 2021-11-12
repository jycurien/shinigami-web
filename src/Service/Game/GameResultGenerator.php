<?php


namespace App\Service\Game;


use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;

class GameResultGenerator
{
    const SCORE_MIN = 50;
    const SCORE_MAX = 500;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Generate random results for a game
     * @param Game $game
     */
    public function generateGameResult(Game $game): void
    {
        if ($game->getUserPlayGames()->isEmpty()) {
            return;
        }

        foreach ($game->getUserPlayGames() as $userPlayGame) {
            $userPlayGame->setScore(rand(self::SCORE_MIN, self::SCORE_MAX));
        }

        $game->setStatus('finished');

        $this->entityManager->flush();
    }
}