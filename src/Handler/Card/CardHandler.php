<?php


namespace App\Handler\Card;


use App\Service\Api\ApiClient;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class CardHandler
{
    /**
     * @var ApiClient
     */
    private $apiClient;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    public function __construct(ApiClient $apiClient, FlashBagInterface $flashBag)
    {
        $this->apiClient = $apiClient;
        $this->flashBag = $flashBag;
    }

    public function updateActivationDateCard($number)
    {
        $body = json_encode(["cardNumber" => $number]);
        $response = $this->apiClient->request('PUT', '/cards/'.$number, $body);

        $card = $response["res"];
        // If card doesn't exists OR cannot be updated because the activation Date is already set
        if(null == $card) {
            $this->flashBag->add('error', 'card.activate.error');
        }

        return $card;
    }

    public function formatCardNumber($card)
    {
        return $card->centerCode.$card->cardCode.$card->checkSum;
    }
}