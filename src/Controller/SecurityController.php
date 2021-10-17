<?php


namespace App\Controller;


use App\Dto\UserDto;
use App\Form\UserRegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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
     * @return Response
     */
    public function register(Request $request): Response
    {
        $userDto = new UserDto();
        $registrationForm = $this->createForm(UserRegistrationType::class, $userDto)
            ->handleRequest($request);

        if ($registrationForm->isSubmitted() && $registrationForm->isValid()) {
            dd($registrationForm, $userDto);
        }

        return $this->render('security/register.html.twig', [
            'registration_form' => $registrationForm->createView(),
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