<?php


namespace App\Controller;


use App\Dto\UserChangePasswordDto;
use App\Dto\UserEditDto;
use App\Entity\User;
use App\Form\UserChangePasswordType;
use App\Form\UserEditType;
use App\Handler\User\ChangePasswordHandler;
use App\Handler\User\ProfileEditHandler;
use App\Repository\ContractRepository;
use App\Repository\PricingRepository;
use App\Repository\UserPlayGameRepository;
use App\Repository\UserRepository;
use App\Service\Api\ApiClient;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @param Request $request
     * @param ProfileEditHandler $editHandler
     * @return Response
     */
    public function profileEdit(Request $request, ProfileEditHandler $editHandler): Response
    {
        $userEditDto = new UserEditDto($this->getUser());
        $userPic = null !== $this->getUser()->getImage() ? '/picture/users/' . $this->getUser()->getImage() : '/picture/user.jpg';
        $options = [
            'picture_url' => $this->getParameter('web_url') . $userPic
        ];
        $userEditForm = $this->createForm(UserEditType::class, $userEditDto, $options)
            ->handleRequest($request);

        if ($userEditForm->isSubmitted() && $userEditForm->isValid()) {
            $editHandler->handle($userEditDto, $this->getUser());
            $this->addFlash('success', 'profile.updated');
            return $this->redirectToRoute('user_profile_show');
        }

        return $this->renderForm('user/profile_edit.html.twig', [
            'user' => $this->getUser(),
            'userEditForm' => $userEditForm
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

        return $this->renderForm('user/change_password.html.twig', [
            'change_password_form' => $resetPasswordForm,
            'active' => 'profile'
        ]);
    }

    /**
     * Display fidelity cards in Users profile
     * @param User $user
     * @param ApiClient $apiClient
     * @return Response
     */
    public function userCards(User $user, ApiClient $apiClient): Response
    {
        if (empty($user->getCardNumbers())) {
            return null;
        }

        $cards = [];
        $errorMessage = null;

        foreach ($user->getCardNumbers() as $cardNumber) {
            $response = $apiClient->request('GET', '/cards/code-'.$cardNumber);
            $errorMessage = $response["errorMessage"];
            if ($response["errorMessage"]) {
                break;
            }

            $cards[] = $response["res"];

        }

        return $this->render('component/_user_cards.html.twig', [
            'cards' => $cards,
            'errorMessage' => $errorMessage
        ]);
    }

    /**
     * Display stats in User profile
     * @param User $user
     * @param UserPlayGameRepository $userPlayGameRepository
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function userPlayStats(User $user, UserPlayGameRepository $userPlayGameRepository): Response
    {
        $userPlayGames = $userPlayGameRepository->findByUser($user);

        $victories = $userPlayGameRepository->findSingleVictoriesByUser($user);

        $maxScore = $userPlayGameRepository->findMaxScoreByUser($user);

        return $this->render('component/_user_play_stats.html.twig', [
            'userPlayGames' => $userPlayGames,
            'victories' => $victories,
            'maxScore' => $maxScore
        ]);
    }

    /**
     * Display offers in user profile
     * @param User $user
     * @param UserPlayGameRepository $userPlayGameRepository
     * @param PricingRepository $pricingRepository
     * @return Response
     */
    public function userOffers(User $user, UserPlayGameRepository $userPlayGameRepository, PricingRepository $pricingRepository): Response
    {
        $em = $this->getDoctrine()->getManager();

        $nbOfUserPlayGames = count($userPlayGameRepository->findByUser($user));

        $fidelityPricing = $pricingRepository->findOneBy(['code' => 'fidelity']);

        $remainingBeforeNextFreeGame = $fidelityPricing->getNumberOfGames() - ($nbOfUserPlayGames % $fidelityPricing->getNumberOfGames()) - 1;

        return $this->render('component/_user_offers.html.twig', [
            'remainingBeforeNextFreeGame' => $remainingBeforeNextFreeGame,
            'fidelityPrice' => $fidelityPricing->getAmount()
        ]);
    }

    /**
     * Display all employees
     * @Route("/admin/employes", name="user_employees_admin")
     * @Security("user.isValidateContract() and is_granted('ROLE_ADMIN')")
     * @param ContractRepository $contractRepository
     * @return Response
     */
    public function employeesAdmin(ContractRepository $contractRepository): Response
    {
        $contracts = $contractRepository->findContractsWithUserAndCenter();

        return $this->render('admin/employee/employees.html.twig', [
            'contracts' => $contracts
        ]);
    }

    /**
     * Return suggestions for Users in json format
     * @Route("/admin/ajax/user-suggestion/{value}", name="user_ajax_user_suggestion_admin", methods={"GET"})
     * @Security("user.isValidateContract() and is_granted('ROLE_STAFF')")
     * @param string $value
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function ajaxGetUserSuggestions(string $value, UserRepository $userRepository): JsonResponse
    {
        return  $this->json(['users' => $userRepository->findUserSuggestions($value)]);
    }

    /**
     * Return User's username and picure url in json format
     * @Route("/admin/ajax/find-user/{id<\d+>}", name="user_ajax_find_user_admin", methods={"GET"})
     * @Security("user.isValidateContract() and is_granted('ROLE_STAFF')")
     * @param User $user
     * @return JsonResponse
     */
    public function ajaxGetUser(User $user): JsonResponse
    {
        return $this->json([
            'username' => $user->getUsername(),
            'picture' => (null != $user->getImage())? '/picture/users/'.$user->getImage() : '/picture/user.jpg' ,
        ]);
    }
}