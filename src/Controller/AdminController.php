<?php


namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_index", methods={"GET"})
     * @Security("user.isValidateContract() and is_granted('ROLE_STAFF')")
     */
    public function index()
    {
        /** @var User $user */
        $user = $this->getUser();
        $center = $user->getContract()->getCenter();
//        $gamesFinished = $this->getDoctrine()->getRepository(Game::class)->findGamesByCenterAndStatus($center, 'finished');
//        $gamesCreated = $this->getDoctrine()->getRepository(Game::class)->findGamesByCenterAndStatus($center, 'created');

        return $this->render("admin/index.html.twig", [
            'user' => $user,
//            'gamesFinished' => $gamesFinished,
//            'gamesCreated' => $gamesCreated,
        ]);
    }

//    /**
//     * @Route({
//     *     "en": "/search-account",
//     *     "fr": "/rechercher-un-compte"
//     *      },
//     *     name="search_account_admin",
//     *     methods={"GET", "POST"})
//     * @Security("user.isValidateContract() and has_role('ROLE_STAFF')")
//     * @param Request $request
//     * @param AdminHandler $adminHandler
//     * @return Response
//     */
//    public function searchAccount(Request $request, AdminHandler $adminHandler)
//    {
//        $result = null;
//
//        # Création du Formulaire de recherche
//        $form = $this->createForm(SearchUserType::class)
//            ->handleRequest($request);
//
//        # Vérification des données du Formulaire
//        if ($form->isSubmitted() && $form->isValid()) {
//
//            $data = $form->getData();
//
//            if (null === $data['cardNumber'] && null === $data['username'] && null === $data['email'] ) {
//                $this->addFlash('error', 'search.error');
//            } else {
//
//                $result = $adminHandler->handleSearchUserData($data);
//
//                if(null !== $result['user']) {
//                    $this->addFlash('success', 'search.customer_found');
//                } elseif (null === $result['number']) {
//                    $this->addFlash('error', 'search.customer_not_found');
//                }
//
//            }
//
//        }
//
//        return $this->render("admin/search_account.html.twig", [
//            'form' => $form->createView(),
//            'errorMessage' => $result['errorMessage'],
//            'card' => $result['card'],
//            'user' => $result['user'],
//            'number' => $result['number']
//        ]);
//    }
}