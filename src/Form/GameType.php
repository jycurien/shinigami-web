<?php

namespace App\Form;

use App\Entity\Game;
use App\Entity\Room;
use App\Event\Subscriber\CreateGameSubscriber;
use App\Handler\Game\UserPlayGamesHandler;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class GameType extends AbstractType
{
    /**
     * @var CreateGameSubscriber
     */
    private $createGameSubscriber;
    /**
     * @var UserPlayGamesHandler
     */
    private $userPlayGamesHandler;

    public function __construct(CreateGameSubscriber $createGameSubscriber, UserPlayGamesHandler $userPlayGamesHandler)
    {
        $this->createGameSubscriber = $createGameSubscriber;
        $this->userPlayGamesHandler = $userPlayGamesHandler;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // We get the existing game if editing form, else we set the game to Null
        /** @var Game $game */
        $game = $options['data']['game'] ?? null;

        $builder
            ->addEventSubscriber($this->createGameSubscriber)
            ->add('room', EntityType::class, [
                'constraints' => new Assert\NotBlank(['message' => 'Veuillez sélectionner une Room']),
                'class' => Room::class,
                'label' => 'room.list',
                'choice_label' => function ($room) {
                    return $room->getName() .' ('.$room->getCapacity().')';
                },
                'choice_attr' => function () use ($game) {
                    return  ($game) ? [
                        'data-room' => $game->getRoom()->getId(),
                        'data-center' => $game->getRoom()->getCenter()->getId(),
                    ] : [];
                }
            ])
            ->add('date', DateTimeType::class, [
                'constraints' => new Assert\NotBlank(),
                'data' => ($game) ? $game->getDate() : new \DateTime(),
                'label' => 'game.date'
            ])
            ->add('player_hidden', HiddenType::class, [
                'attr' => ['disabled' => false],
                'data' => ($game) ? $this->userPlayGamesHandler->formatUsersWithTeamsInString($game->getUserPlayGames()) : '',
            ])
            ->add('player', TextType::class, [
                'required' => false,
                'label' => 'player.add',
            ])
            ->add('team', AddTeamType::class, [
                'label' => 'team.add',
                'label_attr' => ['class' => 'pt-0'],
                'data' => [
                    'teams' => ($game) ? $this->userPlayGamesHandler->formatTeamsInString($game->getUserPlayGames()) : null,
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'create',
                'attr' => ['class' => 'btn btn-lg btn-primary']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'translation_domain' => 'form'
        ]);
    }
}
