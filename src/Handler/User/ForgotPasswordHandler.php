<?php


namespace App\Handler\User;


use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class ForgotPasswordHandler
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var CsrfTokenManagerInterface
     */
    private $csrfTokenManager;

    public function __construct(UserRepository $userRepository, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->userRepository = $userRepository;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    public function handle(Request $request): bool
    {
        if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('forgot_password', $request->request->get('_csrf_token')))) {
            return false;
        }

        if (empty($email = $request->request->get('email'))) {
            return false;
        }

        $user = $this->userRepository->findOneBy(['email' => $email]);
        if (null === $user || !$user->isEnabled()) {
//            $request->getSession()->set('forgot_password_email', $email);
//            return false;
        }

        dd($user);
    }
}