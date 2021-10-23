<?php


namespace App\Service\User;


class VerifyEmailSignatureComponents
{
    /**
     * @var \DateTimeInterface
     */
    private $expiresAt;
    /**
     * @var string
     */
    private $uri;
    /**
     * @var int
     */
    private $generatedAt;

    public function __construct(\DateTimeInterface $expiresAt, string $uri, int $generatedAt)
    {
        $this->expiresAt = $expiresAt;
        $this->uri = $uri;
        $this->generatedAt = $generatedAt;
    }
}