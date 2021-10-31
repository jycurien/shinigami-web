<?php

namespace App\Security;


use App\Security\Authentication\Badge\EnabledUserBadge;
use App\Security\Authentication\PasswordAuthenticationSuccessHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\HttpUtils;

class PasswordAuthenticator extends AbstractLoginFormAuthenticator
{

    /**
     * @var HttpUtils
     */
    private $httpUtils;
    /**
     * @var UserProviderInterface
     */
    private $userProvider;
    /**
     * @var PasswordAuthenticationSuccessHandler
     */
    private $successHandler;
    /**
     * @var array
     */
    private $options;

    public function __construct(HttpUtils $httpUtils, UserProviderInterface $userProvider, PasswordAuthenticationSuccessHandler $successHandler, array $options)
    {
        $this->httpUtils = $httpUtils;
        $this->userProvider = $userProvider;
        $this->successHandler = $successHandler;
        $this->options = array_merge([
            'username_parameter' => '_email',
            'password_parameter' => '_password',
            'enable_csrf' => false,
            'csrf_parameter' => '_csrf_token',
            'csrf_token_id' => 'authenticate',
        ], $options);
    }

    /**
     * @inheritDoc
     */
    protected function getLoginUrl(Request $request): string
    {
        return $this->httpUtils->generateUri($request, $this->options['login_path']);
    }

    /**
     * @inheritDoc
     */
    public function supports(Request $request): bool
    {
        return $request->isMethod('POST')
            && $this->httpUtils->checkRequestPath($request, $this->options['check_path']);
    }

    /**
     * @inheritDoc
     */
    public function authenticate(Request $request): PassportInterface
    {
        $credentials = $this->getCredentials($request);
        $passport = new Passport(
            new UserBadge($credentials['username'], [$this->userProvider, 'loadUserByIdentifier']),
            new PasswordCredentials($credentials['password']),
            [
                new RememberMeBadge(),
                new EnabledUserBadge()
            ]
        );
        if ($this->options['enable_csrf']) {
            $passport->addBadge(new CsrfTokenBadge($this->options['csrf_token_id'], $credentials['csrf_token']));
        }

        return $passport;
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return $this->successHandler->onAuthenticationSuccess($request, $token);
    }

    private function getCredentials(Request $request): array
    {
        $credentials = [];
        $credentials['csrf_token'] = $request->request->get($this->options['csrf_parameter']);
        $credentials['username'] = $request->request->get($this->options['username_parameter'], '');
        $credentials['password'] = $request->request->get($this->options['password_parameter'], '');

        if (!\is_string($credentials['username']) && (!\is_object($credentials['username']) || !method_exists($credentials['username'], '__toString'))) {
            throw new BadRequestHttpException(sprintf('The key "%s" must be a string, "%s" given.', $this->options['username_parameter'], \gettype($credentials['username'])));
        }

        $credentials['username'] = trim($credentials['username']);

        if (\strlen($credentials['username']) > Security::MAX_USERNAME_LENGTH) {
            throw new BadCredentialsException('Invalid username.');
        }

        $request->getSession()->set(Security::LAST_USERNAME, $credentials['username']);

        return $credentials;
    }
}