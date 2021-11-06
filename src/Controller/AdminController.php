<?php


namespace App\Controller;

use App\Entity\User;
use App\Form\SearchUserType;
use App\Handler\Admin\AdminHandler;
use App\Repository\GameRepository;
use App\Repository\RoomRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_index", methods={"GET"})
     * @Security("user.isValidateContract() and is_granted('ROLE_STAFF')")
     * @param GameRepository $gameRepository
     * @param RoomRepository $roomRepository
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function index(GameRepository $gameRepository, RoomRepository $roomRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $center = $user->getContract()->getCenter();

        return $this->render("admin/index.html.twig", [
            'user' => $user,
            'nbRooms' => $roomRepository->countRoomsByCenter($center),
            'nbGamesFinished' => $gameRepository->countGamesByCenterAndStatus($center, 'finished'),
            'nbGamesCreated' => $gameRepository->countGamesByCenterAndStatus($center, 'created')
        ]);
    }

    /**
     * @Route({
     *     "en": "/search-account",
     *     "fr": "/rechercher-un-compte"
     *      },
     *     name="search_account_admin",
     *     methods={"GET", "POST"})
     * @Security("user.isValidateContract() and is_granted('ROLE_STAFF')")
     * @param Request $request
     * @param AdminHandler $adminHandler
     * @return Response
     */
    public function searchAccount(Request $request, AdminHandler $adminHandler)
    {
        $result = null;

        $form = $this->createForm(SearchUserType::class)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            if (null === $data['cardNumber'] && null === $data['username'] && null === $data['email'] ) {
                $this->addFlash('error', 'search.error');
            } else {

                $result = $adminHandler->handleSearchUserData($data);

                if(null !== $result['user']) {
                    $this->addFlash('success', 'search.customer_found');
                } elseif (null === $result['number']) {
                    $this->addFlash('error', 'search.customer_not_found');
                }

            }

        }

        return $this->render("admin/search_account.html.twig", [
            'form' => $form->createView(),
            'errorMessage' => $result['errorMessage'] ?? null,
            'card' => $result['card'] ?? null,
            'user' => $result['user'] ?? null,
            'number' => $result['number'] ?? null
        ]);
    }
}