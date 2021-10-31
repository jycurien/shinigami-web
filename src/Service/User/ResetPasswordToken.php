<?php


namespace App\Service\User;


class ResetPasswordToken
{
    /**
     * @var string|null selector + non-hashed verifier token
     */
    private $token;

    /**
     * @var \DateTimeInterface
     */
    private $expiresAt;

    /**
     * @var int|null timestamp when the token was created
     */
    private $generatedAt;

    public function __construct(string $token, \DateTimeInterface $expiresAt, int $generatedAt = null)
    {
        $this->token = $token;
        $this->expiresAt = $expiresAt;
        $this->generatedAt = $generatedAt;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getExpiresAt(): \DateTimeInterface
    {
        return $this->expiresAt;
    }

    /**
     * Returns the full token the user should use.
     *
     * Internally, this consists of two parts - the selector and
     * the hashed token - but that's an implementation detail
     * of how the token will later be parsed.
     */
    public function getToken(): string
    {
        return $this->token;
    }
}