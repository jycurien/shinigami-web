<?php


namespace App\Handler\User;


use App\Repository\UserRepository;
use App\Service\User\VerifyEmailHelper;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;

class RegistrationConfirmationHandler
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var VerifyEmailHelper
     */
    private $verifyEmailHelper;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(UserRepository $userRepository, VerifyEmailHelper $verifyEmailHelper, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->verifyEmailHelper = $verifyEmailHelper;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function handle(Request $request): bool
    {
        $id = $request->get('id'); // retrieve the user id from the url

        if (null === $id) {
            return false;
        }

        $user = $this->userRepository->find($id);

        // Ensure the user exists in persistence
        if (null === $user) {
            return false;
        }

        if ($this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail())) {
            $user->setEnabled(true);
            $this->entityManager->flush();
            return true;
        } else {
            return false;
        }
    }
}