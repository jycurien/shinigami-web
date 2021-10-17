<?php


namespace App\Factory;


use App\Dto\UserRegistrationDto;
use App\Entity\User;

class UserFactory
{
    public function createFromRegistrationDto(UserRegistrationDto $userDto)
    {
        $user = new User();
        $user->setUsername($userDto->username);
        $user->setEmail($userDto->email);

        return $user;
    }
}