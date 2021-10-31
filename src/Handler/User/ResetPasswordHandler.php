<?php


namespace App\Handler\User;


use App\Dto\UserChangePasswordDto;
use App\Entity\User;
use App\Repository\ResetPasswordRequestRepository;
use App\Repository\UserRepository;
use App\Service\User\ResetPasswordHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResetPasswordHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var UserPasswordHasherInterface
     */
    private $passwordHasher;
    /**
     * @var ResetPasswordRequestRepository
     */
    private $resetPasswordRequestRepository;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, ResetPasswordRequestRepository $resetPasswordRequestRepository)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->resetPasswordRequestRepository = $resetPasswordRequestRepository;
    }

    public function handle(UserChangePasswordDto $changePasswordDto, User $user): void
    {
        $user->setPassword($this->passwordHasher->hashPassword($user, $changePasswordDto->newPassword));
        $this->resetPasswordRequestRepository->removeResetPasswordRequest($user);
        $this->entityManager->flush();
    }
}