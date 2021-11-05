<?php


namespace App\Security\Authentication;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class PasswordAuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    {
        $user = $token->getUser();

        // Si staff on redirige vers l'admin
        if (in_array('ROLE_STAFF', $user->getRoles(), true) || in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return new RedirectResponse($this->urlGenerator->generate('admin_index'));
        }
        // c'est un utilisateur lambda : on le redirige vers l'accueil
        return new RedirectResponse($this->urlGenerator->generate('user_profile_show'));
    }
}