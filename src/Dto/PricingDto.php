<?php


namespace App\Dto;


use App\Entity\Pricing;

class PricingDto
{
    public $code;
    public $description;
    public $amount;
    public $numberOfGames;
    public $numberOfPlayers;

    public function __construct(?Pricing $pricing = null)
    {
        if (null !== $pricing) {
            $this->code = $pricing->getCode();
            $this->description = $pricing->getDescription();
            $this->amount = $pricing->getAmount();
            $this->numberOfGames = $pricing->getNumberOfGames();
            $this->numberOfPlayers = $pricing->getNumberOfPlayers();
        }
    }
}