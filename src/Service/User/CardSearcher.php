<?php


namespace App\Service\User;


use App\Repository\UserRepository;
use App\Service\Api\ApiClient;
use App\Service\QrCodeGenerator;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class CardSearcher
{
    /**
     * @var ApiClient
     */
    private $apiClient;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;
    /**
     * @var QrCodeGenerator
     */
    private $qrCodeGenerator;
    /**
     * @var string
     */
    private $qrCodesImageDirectory;

    public function __construct(ApiClient $apiClient, UserRepository $userRepository, FlashBagInterface $flashBag, QrCodeGenerator $qrCodeGenerator, string $qrCodesImageDirectory)
    {
        $this->apiClient = $apiClient;
        $this->userRepository = $userRepository;
        $this->flashBag = $flashBag;
        $this->qrCodeGenerator = $qrCodeGenerator;
        $this->qrCodesImageDirectory = $qrCodesImageDirectory;
    }

    public function searchAccountCard(string $number)
    {
        // API
        $response = $this->apiClient->request('GET', '/cards/code-' . $number);

        $card = $response["res"];
        $errorMessage = $response["errorMessage"];
        $user = null;

        if ($card != null) {
            $user = $this->userRepository->findByCardNumber($number);

            if ($user != null) {
                $filename = $this->qrCodesImageDirectory.$number.'.png';

                if (!file_exists($filename)) {
                    $this->qrCodeGenerator->generate($number);
                }

            } else {
                $this->flashBag->add('notice', "Cette carte n'est rattachée à aucun client.");
            }

        } else {
            $this->flashBag->add('error',
                "Ce numéro de carte n'existe pas.");
        }

        return [
            'card' => $card,
            'errorMessage' => $errorMessage,
            'user' => $user
        ];
    }
}