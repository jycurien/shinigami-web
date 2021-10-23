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
    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 6,
     *      max = 255
     * )
     * @Assert\NotCompromisedPassword()
     */
    public $password;

    public function __construct(?string $username = null, ?string $email = null, ?string $password = null)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }
}