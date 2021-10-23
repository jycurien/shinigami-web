<?php


namespace App\Service\User;


use Symfony\Component\HttpKernel\UriSigner;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class VerifyEmailHelper
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;
    /**
     * @var UriSigner
     */
    private $uriSigner;
    /**
     * @var string
     */
    private $signingKey;
    /**
     * @var int The length of time in seconds that a signed URI is valid for after it is created
     */
    private $lifetime;

    public function __construct(UrlGeneratorInterface $urlGenerator, UriSigner $uriSigner, string $signingKey, int $lifetime)
    {
        $this->urlGenerator = $urlGenerator;
        $this->uriSigner = $uriSigner;
        $this->signingKey = $signingKey;
        $this->lifetime = $lifetime;
    }

    /**
     * @param string $userId
     * @param string $email
     * @return string
     */
    private function createToken(string $userId, string $email): string
    {
        $encodedData = json_encode([$userId, $email]);

        return base64_encode(hash_hmac('sha256', $encodedData, $this->signingKey, true));
    }

    /**
     * @param string $uri
     * @return array
     */
    private function getQueryParams(string $uri): array
    {
        $params = [];
        $urlComponents = parse_url($uri);

        if (\array_key_exists('query', $urlComponents)) {
            parse_str(($urlComponents['query'] ?? ''), $params);
        }

        return $params;
    }

    /**
     * @param string $routeName
     * @param string $userId
     * @param string $userEmail
     * @param array $extraParams
     * @return VerifyEmailSignatureComponents
     */
    public function generateSignature(string $routeName, string $userId, string $userEmail, array $extraParams = []): VerifyEmailSignatureComponents
    {
        $generatedAt = time();
        $expiryTimestamp = $generatedAt + $this->lifetime;

        $extraParams['token'] = $this->createToken($userId, $userEmail);
        $extraParams['expires'] = $expiryTimestamp;

        $uri = $this->urlGenerator->generate($routeName, $extraParams, UrlGeneratorInterface::ABSOLUTE_URL);

        $signature = $this->uriSigner->sign($uri);

        return new VerifyEmailSignatureComponents(\DateTimeImmutable::createFromFormat('U', (string) $expiryTimestamp), $signature, $generatedAt);
    }

    /**
     * @param string $signedUrl
     * @param string $userId
     * @param string $userEmail
     * @return bool
     */
    public function validateEmailConfirmation(string $signedUrl, string $userId, string $userEmail): bool
    {
        if (!$this->uriSigner->check($signedUrl)) {
            return false;
        }

        $queryParams = $this->getQueryParams($signedUrl);
        if (!isset($queryParams['expires']) || (int) $queryParams['expires'] <= time()) {
            return false;
        }

        $knownToken = $this->createToken($userId, $userEmail);
        $userToken = $queryParams['token'] ?? null;

        if (null === $userToken || !hash_equals($knownToken, $userToken)) {
            return false;
        }

        return true;
    }
}