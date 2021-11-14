<?php


namespace App\Service\Api;


use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiClient
{
    /**
     * @var HttpClientInterface
     */
    private $client;
    /**
     * @var string
     */
    private $apiUrl;
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var string
     */
    private $apiTokenUrl;
    /**
     * @var string
     */
    private $apiUser;
    /**
     * @var string
     */
    private $apiPassword;
    private $token;

    public function __construct(HttpClientInterface $client, string $apiUrl, SessionInterface $session, string $apiTokenUrl, string $apiUser, string $apiPassword)
    {
        $this->client = $client;
        $this->apiUrl = $apiUrl;
        $this->session = $session;
        $this->apiTokenUrl = $apiTokenUrl;
        $this->apiUser = $apiUser;
        $this->apiPassword = $apiPassword;

        // check if a token already exists in session
        if (null !== $session->get('shinigami-token') && time() < $session->get('shinigami-token-expiration')) {
            $this->token = $session->get('shinigami-token');
        } else {
            // get the token from API
            $this->token = $this->getToken()['token'];
            // store it in session
            $session->set('shinigami-token', $this->token);
            $session->set('shinigami-token-expiration', time() + 3600);
        }
    }

    public function request(string $method, string $url, $body = null)
    {
        $errorMessage = null;
        $res = null;

        try {
            $res = $this->client->request($method, $this->apiUrl.$url, [
                'verify_peer' => false,
                'verify_host' => false,
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer '.$this->token
                ],
                'body' => $body
            ]);

            $res = json_decode($res->getContent());
        } catch (TransportException $e) {
            $errorMessage = $e->getMessage();
        }

        return [
            'res' => $res,
            'errorMessage' => $errorMessage
        ];
    }

    /**
     * Get the identification token from the API
     * @return array
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    private function getToken(): array
    {
        $errorMessage = null;
        $res = null;

        try {
            $res = $this->client->request('POST', $this->apiTokenUrl, [
                'verify_peer' => false,
                'verify_host' => false,
                'auth_basic' => [$this->apiUser, $this->apiPassword]
            ]);
        } catch (TransportException $e) {
            $errorMessage = $e->getMessage();
        }

        $token = (null !== $res) ? json_decode($res->getContent())->token : null;

        return [
            'token' => $token,
            'errorMessage' => $errorMessage
        ];
    }
}