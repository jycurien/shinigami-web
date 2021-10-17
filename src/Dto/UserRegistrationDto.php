<?php


namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as AppAssert;

class UserRegistrationDto
{
    /**
     * @var null|string
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 255
     * )
     * @AppAssert\UniqueFieldConstraint(entityRepository="App\Repository\UserRepository")
     */
    public $username;
    /**
     * @var null|string
     * @Assert\NotBlank()
     * @Assert\Email()
     * @AppAssert\UniqueFieldConstraint(entityRepository="App\Repository\UserRepository")
     */
    public $email;

    public function __construct(?string $username = null, ?string $email = null)
    {
        $this->username = $username;
        $this->email = $email;
    }
}