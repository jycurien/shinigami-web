<?php

namespace App\Entity;

use App\Repository\PricingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PricingRepository::class)
 */
class Pricing
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $code;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numberOfGames;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numberOfPlayers;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getNumberOfGames(): ?int
    {
        return $this->numberOfGames;
    }

    public function setNumberOfGames(?int $numberOfGames): self
    {
        $this->numberOfGames = $numberOfGames;

        return $this;
    }

    public function getNumberOfPlayers(): ?int
    {
        return $this->numberOfPlayers;
    }

    public function setNumberOfPlayers(?int $numberOfPlayers): self
    {
        $this->numberOfPlayers = $numberOfPlayers;

        return $this;
    }
}
