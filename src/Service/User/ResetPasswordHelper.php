<?php


namespace App\Service\User;


use App\Repository\ResetPasswordRequestRepository;
use App\Service\RandomStringGenerator;

class ResetPasswordHelper
{
    /**
     * The first 20 characters of the token are a "selector".
     */
    private const SELECTOR_LENGTH = 20;
    /**
     * @var ResetPasswordRequestRepository
     */
    private $repository;
    /**
     * @var RandomStringGenerator
     */
    private $randomStringGenerator;
    /**
     * @var string
     */
    private $signingKey;
    /**
     * @var int
     */
    private $lifetime;

    public function __construct(ResetPasswordRequestRepository $repository, RandomStringGenerator $randomStringGenerator, string $signingKey, int $lifetime)
    {
        $this->repository = $repository;
        $this->randomStringGenerator = $randomStringGenerator;
        $this->signingKey = $signingKey;
        $this->lifetime = $lifetime;
    }

    public function generateResetToken(object $user): ResetPasswordToken
    {
        // TODO
    }

    private function createToken(\DateTimeInterface $expiresAt, $userId): ResetPasswordTokenComponents
    {
        $verifier = $this->randomStringGenerator->getRandomAlphaNumStr();
        $selector = $this->randomStringGenerator->getRandomAlphaNumStr();

        $encodedData = json_encode([$verifier, $userId, $expiresAt->getTimestamp()]);

        return new ResetPasswordTokenComponents(
            $selector,
            $verifier,
            $this->getHashedToken($encodedData)
        );
    }

    private function getHashedToken(string $data): string
    {
        return base64_encode(hash_hmac('sha256', $data, $this->signingKey, true));
    }
}