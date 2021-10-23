<?php


namespace App\Service\User;


class VerifyEmailSignatureComponents
{
    /**
     * @var \DateTimeInterface
     */
    private $expiresAt;
    /**
     * @var string the full signed URL that should be sent to the user
     */
    private $signedUrl;
    /**
     * @var int
     */
    private $generatedAt;

    public function __construct(\DateTimeInterface $expiresAt, string $signedUrl, int $generatedAt)
    {
        $this->expiresAt = $expiresAt;
        $this->signedUrl = $signedUrl;
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
     * @return string
     */
    public function getSignedUrl(): string
    {
        return $this->signedUrl;
    }


}