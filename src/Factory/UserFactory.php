<?php


namespace App\Factory;


use App\Dto\UserRegistrationDto;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactory
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function createFromRegistrationDto(UserRegistrationDto $userDto)
    {
        $user = new User();
        $user->setUsername($userDto->username);
        $user->setEmail($userDto->email);
        $user->setPassword($this->passwordHasher->hashPassword($user, $userDto->password));

        return $user;
    }
}