<?php


namespace App\Handler\User;


use App\Dto\EmployeeDto;
use App\Entity\User;
use App\Event\NewEmployeeEvent;
use App\Factory\UserFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class EmployeeHandler
{
    /**
     * @var UserFactory
     */
    private $userFactory;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    public function __construct(UserFactory $userFactory, EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher, FlashBagInterface $flashBag)
    {
        $this->userFactory = $userFactory;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->flashBag = $flashBag;
    }

    /**
     * @param EmployeeDto $employee
     * @param User|null $user
     * @return User
     */
    public function handle(EmployeeDto $employee, ?User $user = null): User
    {
        if (null === $user) {
            $user = $this->userFactory->createFromEmployeeDto($employee);
            $this->flashBag->add('success', 'contract.employee_added');
            $this->entityManager->persist($user);
            $this->eventDispatcher->dispatch(new NewEmployeeEvent($user), NewEmployeeEvent::NAME);
        }

        $this->entityManager->flush();
        return $user;
    }
}