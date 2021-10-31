<?php


namespace App\Handler\User;


use App\Repository\UserRepository;
use App\Service\User\ResetPasswordHelper;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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
    /**
     * @var ResetPasswordHelper
     */
    private $resetPasswordHelper;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(
        UserRepository $userRepository,
        CsrfTokenManagerInterface $csrfTokenManager,
        ResetPasswordHelper $resetPasswordHelper,
        UrlGeneratorInterface $urlGenerator,
        MailerInterface $mailer)
    {
        $this->userRepository = $userRepository;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->resetPasswordHelper = $resetPasswordHelper;
        $this->urlGenerator = $urlGenerator;
        $this->mailer = $mailer;
    }

    public function handle(Request $request, string $fromEmailAddress): bool
    {
        if (!$this->csrfTokenManager->isTokenValid(new CsrfToken('forgot_password', $request->request->get('_csrf_token')))) {
            return false;
        }

        if (empty($email = $request->request->get('email'))) {
            return false;
        }

        $user = $this->userRepository->findOneBy(['email' => $email]);
        if (null === $user || !$user->isEnabled()) {
            $request->attributes->set('forgot_password_email', $email);
            return false;
        }

        $resetPasswordToken = $this->resetPasswordHelper->generateResetToken($user);

        if (null === $resetPasswordToken) {
            return false;
        }

        $resetUrl = $this->urlGenerator->generate('security_reset_password', ['token' => $resetPasswordToken->getToken()], UrlGeneratorInterface::ABSOLUTE_URL);
        $email = new TemplatedEmail();
        $email->from($fromEmailAddress);
        $email->to($user->getEmail());
        $email->subject('Shinigami Laser - Réinitialisation de votre mot de passe'); // TODO translate
        $email->htmlTemplate('email/user/forgot_password_email.html.twig');
        $email->context([
            'username' => $user->getUsername(),
            'reset_url' => $resetUrl,
            'linkExpiresAt' => $resetPasswordToken->getExpiresAt()
        ]);

        $this->mailer->send($email);

        return true;
    }
}