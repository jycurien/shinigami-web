<?php


namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as AppAssert;

class UserChangePasswordDto
{
    // TODO add @Assert\NotCompromisedPassword()
    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 6,
     *      max = 255
     * )
     */
    public $newPassword;
    /**
     * @var string|null
     * @AppAssert\UserPasswordConstraint(groups={"update_password"})
     */
    public $oldPassword;

    public function __construct(?string $newPassword = null, ?string $oldPassword = null)
    {
        $this->newPassword = $newPassword;
        $this->oldPassword = $oldPassword;
    }
}