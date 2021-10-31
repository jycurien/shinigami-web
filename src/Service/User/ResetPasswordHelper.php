<?php


namespace App\Service\User;


use App\Entity\ResetPasswordRequest;
use App\Entity\User;
use App\Repository\ResetPasswordRequestRepository;
use App\Service\RandomStringGenerator;
use Doctrine\ORM\EntityManagerInterface;

class ResetPasswordHelper
{
    /**
     * The first 20 characters of the token are a "selector".
     */
    private const SELECTOR_LENGTH = 20;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
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
     * @var int How long a token is valid in seconds
     */
    private $lifetime;
    /**
     * @var int Another password reset cannot be made faster than this throttle time in seconds
     */
    private $requestThrottleTime;

    public function __construct(
        EntityManagerInterface $entityManager,
        ResetPasswordRequestRepository $repository,
        RandomStringGenerator $randomStringGenerator,
        string $signingKey,
        int $lifetime,
        int $requestThrottleTime)
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
        $this->randomStringGenerator = $randomStringGenerator;
        $this->signingKey = $signingKey;
        $this->lifetime = $lifetime;
        $this->requestThrottleTime = $requestThrottleTime;
    }

    public function generateResetToken(User $user): ?ResetPasswordToken
    {
        if ($this->getAvailableAt($user) > new \DateTime('now')) {
            return null;
        }

        $this->repository->removeResetPasswordRequest($user);

        $expiresAt = new \DateTimeImmutable(sprintf('+%d seconds', $this->lifetime));

        $generatedAt = ($expiresAt->getTimestamp() - $this->lifetime);

        $tokenComponents = $this->createToken($expiresAt, $user->getId());

        $passwordResetRequest = new ResetPasswordRequest(
            $tokenComponents->getSelector(),
            $tokenComponents->getHashedToken(),
            $expiresAt,
            $user
        );

        $this->entityManager->persist($passwordResetRequest);
        $this->entityManager->flush();

        // final "public" token is the selector + non-hashed verifier token
        return new ResetPasswordToken(
            $tokenComponents->getPublicToken(),
            $expiresAt,
            $generatedAt
        );
    }

    public function validateTokenAndFetchUser(string $fullToken): ?User
    {
        if (40 !== \strlen($fullToken)) {
            return null;
        }

        $selector = substr($fullToken, 0, self::SELECTOR_LENGTH);


        $resetRequest = $this->repository->findResetPasswordRequest($selector);

        if (null === $resetRequest) {
            return null;
        }

        if ($resetRequest->isExpired()) {
            return null;
        }

        $user = $resetRequest->getUser();

        $hashedVerifierToken = $this->createToken(
            $resetRequest->getExpiresAt(),
            $user->getId(),
            substr($fullToken, self::SELECTOR_LENGTH)
        );

        if (false === hash_equals($resetRequest->getHashedToken(), $hashedVerifierToken->getHashedToken())) {
            return null;
        }

        return $user;
    }

    private function createToken(\DateTimeInterface $expiresAt, $userId, string $verifier = null): ResetPasswordTokenComponents
    {
        if (null === $verifier) {
            $verifier = $this->randomStringGenerator->getRandomAlphaNumStr();
        }

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

    private function getAvailableAt(User $user): ?\DateTimeInterface
    {
        /** @var \DateTime|\DateTimeImmutable|null $lastRequestDate */
        $lastRequestDate = $this->repository->getMostRecentNonExpiredRequestDate($user);

        if (null !== $lastRequestDate) {
            return (clone $lastRequestDate)->add(new \DateInterval("PT{$this->requestThrottleTime}S"));
        }

        return new \DateTime('now');
    }
}