<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Room::class, inversedBy="games")
     * @ORM\JoinColumn(nullable=false)
     */
    private $room;

    /**
     * @ORM\OneToMany(targetEntity=UserPlayGame::class, mappedBy="game", orphanRemoval=true)
     */
    private $userPlayGames;

    public function __construct()
    {
        $this->date = new \DateTimeImmutable();
        $this->status = 'created';
        $this->userPlayGames = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }

    /**
     * @return Collection|UserPlayGame[]
     */
    public function getUserPlayGames(): Collection
    {
        return $this->userPlayGames;
    }

    public function addUserPlayGame(UserPlayGame $userPlayGame): self
    {
        if (!$this->userPlayGames->contains($userPlayGame)) {
            $this->userPlayGames[] = $userPlayGame;
            $userPlayGame->setGame($this);
        }

        return $this;
    }

    public function removeUserPlayGame(UserPlayGame $userPlayGame): self
    {
        if ($this->userPlayGames->removeElement($userPlayGame)) {
            // set the owning side to null (unless already changed)
            if ($userPlayGame->getGame() === $this) {
                $userPlayGame->setGame(null);
            }
        }

        return $this;
    }
}
