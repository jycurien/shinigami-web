<?php


namespace App\Handler\Admin;


use App\Repository\UserRepository;

class AdminHandler
{
    private $userRepository, $cardSearcher;

    /**
     * AdminHandler constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository /* , CardSearcher $cardSearcher */)
    {
        $this->userRepository = $userRepository;
//        $this->cardSearcher = $cardSearcher;
    }

    /**
     * Handle the data submitted in the search account form
     * @param array $data
     * @return array
     */
    public function handleSearchUserData(array $data): array
    {
        $card = null;
        $errorMessage = null;
        $user = null;
        $number = null;

        // TODO Search by card number
//        if (null !== $data['cardNumber']) {
//            $number = $data['cardNumber'];
//            $infos = $this->cardSearcher->searchAccountCard($number);
//
//            $card = $infos['card'];
//            $errorMessage = $infos['errorMessage'];
//            $user = $infos['user'];
//        }

        if ( null === $user && null !== $data['username'] ) {
            // Search by username
            $user = $this->userRepository->findOneByUsername($data['username']);
        }

        if ( null === $user && null !== $data['email'] ) {
            // Search by email
            $user = $this->userRepository->findOneByEmail($data['email']);
        }

        return [
            'card' => $card,
            'errorMessage' => $errorMessage,
            'user' => $user,
            'number' => $number
        ];
    }
}