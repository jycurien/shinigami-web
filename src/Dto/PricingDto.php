<?php


namespace App\Dto;


use App\Entity\Pricing;
use Symfony\Component\Validator\Constraints as Assert;

class PricingDto
{
    /**
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 2,
     *      max = 50
     * )
     */
    public $code;
    /**
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 2,
     *      max = 1024
     * )
     */
    public $description;
    /**
     * @Assert\NotBlank
     * @Assert\PositiveOrZero
     */
    public $amount;
    /**
     * @Assert\PositiveOrZero
     */
    public $numberOfGames;
    /**
     * @Assert\PositiveOrZero
     */
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