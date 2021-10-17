<?php


namespace App\Handler\User;


use App\Dto\UserRegistrationDto;
use App\Entity\User;
use App\Factory\UserFactory;
use Doctrine\ORM\EntityManagerInterface;

class RegistrationHandler
{
    /**
     * @var UserFactory
     */
    private $userFactory;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(UserFactory $userFactory, EntityManagerInterface $entityManager)
    {
        $this->userFactory = $userFactory;
        $this->entityManager = $entityManager;
    }

    /**
     * @param UserRegistrationDto $userDto
     * @return User
     */
    public function handle(UserRegistrationDto $userDto): User
    {
        $user = $this->userFactory->createFromRegistrationDto($userDto);

        // generate validation token and send email
        return $user;
    }
}