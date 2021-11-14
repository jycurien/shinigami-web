<?php


namespace App\DataFixtures;


use App\Entity\Game;
use App\Entity\UserPlayGame;
use App\Service\GamePriceCalculator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class GameFixtures extends Fixture implements DependentFixtureInterface
{
    private $priceCalculator;

    /**
     * GameFixtures constructor.
     * @param GamePriceCalculator $priceCalculator
     */
    public function __construct(GamePriceCalculator $priceCalculator)
    {
        $this->priceCalculator = $priceCalculator;
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 100; $i++) {
            $game = new Game();
            $day = rand(1,30);
            $month = rand(1,11);
            $year = 2021;
            $date = new \DateTime();
            $date->setDate($year, $month, $day);
            $date->setTime(rand(10, 18), 0, 0);
            $game->setDate($date);
            $game->setStatus('finished');
            $game->setRoom($this->getReference('room'.($i%5)));

            $nbOfPlayers = rand(4, 14);

            for ($j = 0; $j < $nbOfPlayers; $j++) {
                $userPlayGame = new UserPlayGame();
                $userPlayGame->setScore(rand(50, 500));
                $userPlayGame->setTeam('Team_'.($j%3));
                $user = $this->getReference('user'.$j);
                $user->addUserPlayGame($userPlayGame);
                $game->addUserPlayGame($userPlayGame);
                $manager->persist($userPlayGame);
            }

            // update upg prices
            foreach ($game->getUserPlayGames() as $userPlayGame) {
                $userPlayGame->setPrice($this->priceCalculator->pricePerPlayer($userPlayGame));
                $manager->persist($userPlayGame);
            }
            $manager->persist($game);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return [
            CenterRoomFixtures::class,
            UserFixtures::class
        ];
    }
}