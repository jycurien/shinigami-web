<?php


namespace App\Service\Api;


use Symfony\Component\HttpClient\Exception\TransportException;
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

    public function __construct(HttpClientInterface $client, string $apiUrl)
    {
        $this->client = $client;
        $this->apiUrl = $apiUrl;
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
//                    'Authorization' => 'Bearer '.$this->token
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
}