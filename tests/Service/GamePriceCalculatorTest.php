<?php


namespace App\Tests\Service;


use App\Entity\Game;
use App\Entity\Pricing;
use App\Entity\User;
use App\Entity\UserPlayGame;
use App\Repository\PricingRepository;
use App\Service\GamePriceCalculator;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class GamePriceCalculatorTest extends TestCase
{
    private $pricings;

    public function setUp(): void
    {
        // Set up the pricings

        // standard pricing
        $standardPricing = $this->createMock(Pricing::class);
        $standardPricing->expects($this->once())->method('getCode')->willReturn('standard');
        $standardPricing->expects($this->once())->method('getAmount')->willReturn(30.00);
        $standardPricing->expects($this->once())->method('getNumberOfGames')->willReturn(null);
        $standardPricing->expects($this->once())->method('getNumberOfPlayers')->willReturn(null);

        // fidelity pricing
        $fidelityPricing = $this->createMock(Pricing::class);
        $fidelityPricing->expects($this->once())->method('getCode')->willReturn('fidelity');
        $fidelityPricing->expects($this->once())->method('getAmount')->willReturn(0.00);
        $fidelityPricing->expects($this->once())->method('getNumberOfGames')->willReturn(10);
        $fidelityPricing->expects($this->once())->method('getNumberOfPlayers')->willReturn(null);

        // group pricing
        $groupPricing = $this->createMock(Pricing::class);
        $groupPricing->expects($this->once())->method('getCode')->willReturn('group');
        $groupPricing->expects($this->once())->method('getAmount')->willReturn(25.00);
        $groupPricing->expects($this->once())->method('getNumberOfGames')->willReturn(null);
        $groupPricing->expects($this->once())->method('getNumberOfPlayers')->willReturn(12);

        $this->pricings = [$standardPricing, $fidelityPricing, $groupPricing];

    }

    public function testPricePerPlayer()
    {

        // create mock pricing repository
        $pricingsRepository = $this->createMock(PricingRepository::class);
        $pricingsRepository->expects($this->any())->method('findAll')->willReturn($this->pricings);

        // create 9 passed UserPlayGames
        $upgs = new ArrayCollection();
        for ($i = 0; $i<9; $i++) {
            $upgs->add($this->createMock(UserPlayGame::class));
        }

        // create mock User
        $user = $this->createMock(User::class);
        $user->expects($this->any())->method('getUserPlayGames')->willReturn($upgs);

        // create mock Game
        $game = $this->createMock(Game::class);
        $game->expects($this->any())->method('getUserPlayGames')->willReturn($upgs);

        // create new UserPlayGame
        $newUpg = $this->createMock(UserPlayGame::class);
        $newUpg->expects($this->any())->method('getUser')->willReturn($user);
        $newUpg->expects($this->any())->method('getGame')->willReturn($game);

        $priceCalculator = new GamePriceCalculator($pricingsRepository);

        // test fidelity pricing
        // add the new UserPlayGame to the User's UserPlayGames (now count = 10 OK for fidelity pricing)
        $upgs->add($newUpg);
        $result = $priceCalculator->pricePerPlayer($newUpg);
        $this->assertEquals(0, $result);

        // test standard pricing
        // add 1 new upg (now count = 11, standard pricing should apply)
        $upgs->add($this->createMock(UserPlayGame::class));
        $result = $priceCalculator->pricePerPlayer($newUpg);
        $this->assertEquals(30, $result);

        // test group pricing
        // add 1 new upg (now count = 12, standard pricing should apply)
        $upgs->add($this->createMock(UserPlayGame::class));
        $result = $priceCalculator->pricePerPlayer($newUpg);
        $this->assertEquals(25, $result);
    }
}