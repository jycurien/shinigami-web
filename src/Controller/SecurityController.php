<?php


namespace App\Controller;


use App\Dto\UserChangePasswordDto;
use App\Dto\UserRegistrationDto;
use App\Form\UserChangePasswordType;
use App\Form\UserRegistrationType;
use App\Handler\User\ForgotPasswordHandler;
use App\Handler\User\RegistrationConfirmationHandler;
use App\Handler\User\RegistrationHandler;
use App\Handler\User\ResetPasswordHandler;
use App\Service\User\ResetPasswordHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends  AbstractController
{
    /**
     * @Route({
     *     "en": "/login",
     *     "fr": "/connexion"
     *      }, name="security_login", methods={"GET", "POST"})
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * @Route({
     *     "en": "/register",
     *     "fr": "/inscription"
     *      }, name="security_register", methods={"GET", "POST"})
     * @param Request $request
     * @param RegistrationHandler $registrationHandler
     * @param TranslatorInterface $translator
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function register(Request $request, RegistrationHandler $registrationHandler, TranslatorInterface $translator): Response
    {
        $userDto = new UserRegistrationDto();
        $registrationForm = $this->createForm(UserRegistrationType::class, $userDto)
            ->handleRequest($request);

        if ($registrationForm->isSubmitted() && $registrationForm->isValid()) {
            $registrationHandler->handle($userDto, $this->getParameter('from_email_address'));
            $this->addFlash('success', 'registration.check_email');
            return $this->redirectToRoute('security_login');
        }

        return $this->renderForm('security/register.html.twig', [
            'registration_form' => $registrationForm,
            'error'             => null,
        ]);
    }

    /**
     * @Route({
     *     "en": "/registration_confirmation",
     *     "fr": "/confirmation_inscription"
     *      }, name="security_registration_confirmation", methods={"GET"})
     * @param Request $request
     * @param RegistrationConfirmationHandler $confirmationHandler
     * @return Response
     */
    public function registrationConfirmation(Request $request, RegistrationConfirmationHandler $confirmationHandler): Response
    {
        if ($confirmationHandler->handle($request)) {
            $this->addFlash('success', 'registration.confirmed');
            return $this->redirectToRoute('security_login');
        } else {
            $this->addFlash('error', 'TODO email confirmation error message');
            return $this->redirectToRoute('index');
        }
    }

    /**
     * @Route({
     *     "en": "/forgot_password",
     *     "fr": "/mot_de_passe_oublie"
     *      }, name="security_forgot_password", methods={"GET", "POST"})
     * @param Request $request
     * @param ForgotPasswordHandler $forgotPasswordHandler
     * @return Response
     */
    public function forgotPassword(Request $request, ForgotPasswordHandler $forgotPasswordHandler): Response
    {
        $error = false;
        if ($request->isMethod(Request::METHOD_POST)) {
            $error = !$forgotPasswordHandler->handle($request, $this->getParameter('from_email_address'));
            if (!$error) {
                $this->addFlash('success', 'resetting.check_email');
                return $this->redirectToRoute('security_login');
            }
        }
        $lastEmail = $request->attributes->get('forgot_password_email', '');

        return $this->render('security/forgot_password.html.twig', [
            'last_email' => $lastEmail,
            'error' => $error
        ]);
    }

    /**
     * @Route({
     *     "en": "/reset_password",
     *     "fr": "/reinitialisation_mot_de_passe"
     *      }, name="security_reset_password", methods={"GET", "POST"})
     * @param Request $request
     * @param ResetPasswordHelper $resetPasswordHelper
     * @param ResetPasswordHandler $resetPasswordHandler
     * @return Response
     */
    public function resetPassword(Request $request, ResetPasswordHelper $resetPasswordHelper, ResetPasswordHandler $resetPasswordHandler): Response
    {
       $token = $request->query->get('token');
        if (null === $token) {
            throw new AccessDeniedException('Invalid request parameters');
        }
        $user = $resetPasswordHelper->validateTokenAndFetchUser($token);
        if (null === $user) {
            throw new AccessDeniedException('Invalid request parameters');
        }
        $userChangePasswordDto = new UserChangePasswordDto();
        $resetPasswordForm = $this->createForm(UserChangePasswordType::class, $userChangePasswordDto, [
            'require_old_password' => false
        ])
            ->handleRequest($request);

        if ($resetPasswordForm->isSubmitted() && $resetPasswordForm->isValid()) {
            $resetPasswordHandler->handle($userChangePasswordDto, $user);
            $this->addFlash('success', 'resetting.success');
            return $this->redirectToRoute('security_login');
        }

        return $this->renderForm('security/change_password.html.twig', [
            'change_password_form' => $resetPasswordForm,
            'active' => 'login',
            'error'             => null,
        ]);
    }

    /**
     * @Route("/logout", name="security_logout", methods={"GET"})
     * @throws \Exception
     */
    public function logout(): void
    {
        // controller can be blank: it will never be executed!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}