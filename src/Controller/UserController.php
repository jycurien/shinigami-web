<?php


namespace App\Controller;


use App\Dto\UserChangePasswordDto;
use App\Form\UserChangePasswordType;
use App\Handler\User\ChangePasswordHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{
    /**
     * @Route({
     *     "en": "/my_account",
     *     "fr": "/mon_compte"
     *      },
     *     name="user_profile_show",
     *     methods={"GET"})
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function profileShow(): Response
    {
        return $this->render('user/profile_show.html.twig', [
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route({
     *     "en": "/my_account/edit",
     *     "fr": "/mon_compte/modifier"
     *      },
     *     name="user_profile_edit",
     *     methods={"GET", "POST"})
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function profileEdit(): Response
    {
        return $this->render('user/profile_edit.html.twig', [
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route({
     *     "en": "/my_account/change_password",
     *     "fr": "/mon_compte/modifier_mot_de_passe"
     *      },
     *     name="user_change_password",
     *     methods={"GET", "POST"})
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @param ChangePasswordHandler $changePasswordHandler
     * @return Response
     */
    public function changePassword(Request $request, ChangePasswordHandler $changePasswordHandler): Response
    {
        $userChangePasswordDto = new UserChangePasswordDto();
        $resetPasswordForm = $this->createForm(UserChangePasswordType::class, $userChangePasswordDto, [
            'require_old_password' => true,
            'validation_groups' => ['Default', 'update_password']
        ])
            ->handleRequest($request);

        if ($resetPasswordForm->isSubmitted() && $resetPasswordForm->isValid()) {
            $changePasswordHandler->handle($userChangePasswordDto, $this->getUser());
            $this->addFlash('success', 'change_password.success');
            return $this->redirectToRoute('user_profile_show');
        }

        return $this->render('user/change_password.html.twig', [
            'change_password_form' => $resetPasswordForm->createView(),
            'active' => 'profile'
        ]);
    }
}