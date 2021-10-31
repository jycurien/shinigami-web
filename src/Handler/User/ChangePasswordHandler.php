<?php


namespace App\Handler\User;


use App\Dto\UserChangePasswordDto;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ChangePasswordHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var UserPasswordHasherInterface
     */
    private $passwordHasher;


    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    public function handle(UserChangePasswordDto $changePasswordDto, User $user): void
    {
        $user->setPassword($this->passwordHasher->hashPassword($user, $changePasswordDto->newPassword));
        $this->entityManager->flush();
    }
}