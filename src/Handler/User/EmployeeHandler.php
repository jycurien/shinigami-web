<?php


namespace App\Handler\User;


use App\Dto\EmployeeDto;
use App\Entity\Address;
use App\Entity\Contract;
use App\Entity\User;
use App\Event\NewEmployeeEvent;
use App\Factory\UserFactory;
use App\Service\User\UserImageUploadHelper;
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
    /**
     * @var UserImageUploadHelper
     */
    private $imageUploadHelper;

    public function __construct(UserFactory $userFactory, EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher, FlashBagInterface $flashBag, UserImageUploadHelper $imageUploadHelper)
    {
        $this->userFactory = $userFactory;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->flashBag = $flashBag;
        $this->imageUploadHelper = $imageUploadHelper;
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
            $this->eventDispatcher->dispatch(new NewEmployeeEvent($user), NewEmployeeEvent::NAME);
        } else {
            $userContract = $user->getContract() ?? new Contract();
            $userContract->setStartDate($employee->contract->startDate);
            $userContract->setEndDate($employee->contract->endDate);
            $userContract->setCenter($employee->contract->center);
            $user->setContract($userContract);
            $user->setRoles($employee->roles);
            $user->setEmail($employee->email);
            $user->setUsername($employee->username);
            $user->setFirstName($employee->firstName);
            $user->setLastName($employee->lastName);
            $user->setPhoneNumber($employee->phoneNumber);
            $user->setBirthDate($employee->birthDate);
            $userAddress = $user->getAddress() ?? new Address();
            $userAddress->setAddress($employee->address->address);
            $userAddress->setZipCode($employee->address->zipCode);
            $userAddress->setCity($employee->address->city);
            $user->setAddress($userAddress);
            $this->flashBag->add('success', 'contract.employee_updated');
        }

        // IMAGE UPLOAD
        if (null !== $employee->image) {
            $fileName = $this->imageUploadHelper->uploadImage($employee->image, $user);
            $user->setImage($fileName);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }
}