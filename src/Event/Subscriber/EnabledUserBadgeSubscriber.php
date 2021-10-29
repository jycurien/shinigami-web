<?php


namespace App\Event\Subscriber;


use App\Security\Authentication\Badge\EnabledUserBadge;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;

class EnabledUserBadgeSubscriber implements EventSubscriberInterface
{
    public function onCheckPassportEvent(CheckPassportEvent $event)
    {
        /** @var Passport $passport */
        $passport = $event->getPassport();

        if (!$passport->hasBadge(EnabledUserBadge::class) || !$passport->hasBadge(UserBadge::class)) {
            return;
        }

        /** @var UserBadge $badgeUser */
        $userBadger = $passport->getBadge(UserBadge::class);
        $enabledUserBadge = $passport->getBadge(EnabledUserBadge::class);

        /** @var EnabledUserBadge $enabledUserBadge */
        $enabledUserBadge->check($userBadger->getUser());
    }

    public static function getSubscribedEvents()
    {
        return [
            CheckPassportEvent::class => 'onCheckPassportEvent',
        ];
    }
}