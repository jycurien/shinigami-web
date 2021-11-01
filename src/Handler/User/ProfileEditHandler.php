<?php


namespace App\Handler\User;


use App\Dto\UserEditDto;
use App\Entity\User;

class ProfileEditHandler
{
    public function handle(UserEditDto $userEditDto, User $user)
    {
        dd($userEditDto);
    }
}