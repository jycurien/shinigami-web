<?php


namespace App\Tests\Service;


use App\Entity\Game;
use App\Entity\UserPlayGame;
use App\Service\Game\GameResultGenerator;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class GameResultGeneratorTest extends TestCase
{
    private $gameResultGenerator, $game;

    public function setUp(): void
    {
        $this->gameResultGenerator = New GameResultGenerator($this->createMock(EntityManagerInterface::class));
        $this->game = New Game();
    }

    public function testGenerateGameResultWithoutUserPlayGame()
    {
        $this->gameResultGenerator->generateGameResult($this->game);
        $this->assertNotEquals('finished', $this->game->getStatus());
    }

    public function testGenerateGameResult()
    {
        // create 5 passed UserPlayGames
        for ($i = 0; $i<5; $i++) {
            $upg = $this->createMock(UserPlayGame::class);
            $upg->expects($this->once())->method('setScore');
            $this->game->addUserPlayGame($upg);
        }

        $this->gameResultGenerator->generateGameResult($this->game);

        $this->assertEquals('finished', $this->game->getStatus());
    }
}