<?php

namespace App\Entity;

use App\Repository\ResetPasswordRequestRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResetPasswordRequestRepository::class)
 */
class ResetPasswordRequest
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $selector;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $hashedToken;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $requestedAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $expiresAt;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     */
    private $user;

    /**
     * ResetPasswordRequest constructor.
     * @param $selector
     * @param $hashedToken
     * @param $expiresAt
     * @param $user
     * @throws \Exception
     */
    public function __construct($selector, $hashedToken, $expiresAt, $user)
    {
        $this->selector = $selector;
        $this->hashedToken = $hashedToken;
        $this->requestedAt = new \DateTimeImmutable();
        $this->expiresAt = $expiresAt;
        $this->user = $user;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSelector(): ?string
    {
        return $this->selector;
    }

    public function setSelector(string $selector): self
    {
        $this->selector = $selector;

        return $this;
    }

    public function getHashedToken(): ?string
    {
        return $this->hashedToken;
    }

    public function setHashedToken(string $hashedToken): self
    {
        $this->hashedToken = $hashedToken;

        return $this;
    }

    public function getRequestedAt(): ?\DateTimeImmutable
    {
        return $this->requestedAt;
    }

    public function setRequestedAt(\DateTimeImmutable $requestedAt): self
    {
        $this->requestedAt = $requestedAt;

        return $this;
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(\DateTimeImmutable $expiresAt): self
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function isExpired(): bool
    {
        return $this->expiresAt->getTimestamp() <= time();
    }
}
