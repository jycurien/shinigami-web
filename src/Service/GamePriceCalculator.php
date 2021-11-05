<?php


namespace App\Service;


use App\Entity\UserPlayGame;
use App\Repository\PricingRepository;
use Doctrine\Persistence\ObjectManager;

class GamePriceCalculator
{
    private $pricings;

    /**
     * GamePriceCalculator constructor.
     * @param PricingRepository $pricingRepository
     */
    public function __construct(PricingRepository $pricingRepository)
    {
        $pricings = $pricingRepository->findAll();
        $pricings_keys = array_map(function($pricing) {
            return $pricing->getCode();
        }, $pricings);
        $pricings_values = array_map(function($pricing) {
            return (object) [
                'amount' => $pricing->getAmount(),
                'numberOfGames' => $pricing->getNumberOfGames(),
                'numberOfPlayers' => $pricing->getNumberOfPlayers(),
            ];
        }, $pricings);
        $this->pricings = array_combine($pricings_keys, $pricings_values);
    }

    /**
     * Calculate the price of UserPlayGame
     * @param UserPlayGame $upg
     * @return float
     */
    public function pricePerPlayer(UserPlayGame $upg): float
    {
        $price = $this->pricings['standard']->amount;

        // Check if fidelity pricing applies
        if (0 === count($upg->getUser()->getUserPlayGames()) % $this->pricings['fidelity']->numberOfGames) {
            $price = $this->pricings['fidelity']->amount;
            return $price;
        }

        // Check if group pricing applies
        if (count($upg->getGame()->getUserPlayGames()) >= $this->pricings['group']->numberOfPlayers) {
            $price = $this->pricings['group']->amount;
            return $price;
        }

        return $price;
    }
}