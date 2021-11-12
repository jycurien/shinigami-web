<?php


namespace App\Controller;


use App\Entity\Game;
use App\Form\GameType;
use App\Handler\Game\GameHandler;
use App\Repository\GameRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class GameController extends  AbstractController
{
    /**
     * @Route("/games",
     *     name="game_games_admin",
     *     methods={"GET"})
     * @Security("user.isValidateContract() and is_granted('ROLE_STAFF')")
     * @param GameRepository $gameRepository
     * @return Response
     */
    public function games(GameRepository $gameRepository): Response
    {
        return $this->render("admin/game/games.html.twig", [
            'games' => $gameRepository->findLatestGamesWithCenter()
        ]);
    }

    /**
     * @Route({
     *     "en": "/create-game",
     *     "fr": "/creer-une-partie"
     *      },
     *     name="game_create_admin",
     *     methods={"GET", "POST"})
     * @Security("user.isValidateContract() and is_granted('ROLE_STAFF')")
     * @param Request $request
     * @param GameHandler $gameHandler
     * @return Response
     */
    public function create(Request $request, GameHandler $gameHandler): Response
    {
        $form = $this->createForm(GameType::class)->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if($gameHandler->handle($data, "create")) {
                return $this->redirectToRoute("game_games_admin");
            }
        }

        return $this->render("admin/game/create.html.twig", [
            'form' => $form->createView(),
            'action' => 'create'
        ]);
    }

    /**
     * @Route({
     *     "en": "/update-game/{id<\d+>}",
     *     "fr": "/editer-une-partie/{id<\d+>}"
     *      },
     *     name="game_edit_admin",
     *     methods={"GET", "POST"})
     * @Security("user.isValidateContract() and is_granted('ROLE_STAFF')")
     * @param Request $request
     * @param GameHandler $gameHandler
     * @param Game $game
     * @return Response
     */
    public function edit(Request $request,GameHandler $gameHandler, Game $game): Response
    {
        $form = $this->createForm(GameType::class, ['game' => $game])->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if($gameHandler->handle($data, "update", $game)) {
                return $this->redirectToRoute("game_games_admin");
            }
        }

        return $this->render("admin/game/create.html.twig", [
            'form' => $form->createView(),
            'action' => 'update'
        ]);
    }
//
//    /**
//     * @Route("/play-game/{id<\d+>}", name="game_play_admin")
//     * @Security("user.isValidateContract() and has_role('ROLE_STAFF')")
//     * @param Game $game
//     * @param GameResultGenerator $resultGenerator
//     * @return \Symfony\Component\HttpFoundation\Response
//     */
//    public function play(Game $game, GameResultGenerator $resultGenerator)
//    {
//        if ('created' === $game->getStatus()) {
//            $resultGenerator->generateGameResult($game);
//        }
//
//        return $this->redirectToRoute("game_games_admin");
//    }
}