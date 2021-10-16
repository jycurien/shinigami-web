<?php


namespace App\Security\Authentication\Badge;


use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\BadgeInterface;

class EnabledUserBadge implements BadgeInterface
{
    /**
     * @var bool
     */
    private $resolved;

    public function __construct()
    {
        $this->resolved = false;
    }

    public function check(UserInterface $user)
    {
        $this->resolved = $user->isEnabled();
    }

    public function isResolved(): bool
    {
        return  $this->resolved;
    }
}