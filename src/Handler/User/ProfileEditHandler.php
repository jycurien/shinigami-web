<?php


namespace App\Handler\User;


use App\Dto\UserEditDto;
use App\Entity\Address;
use App\Entity\User;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProfileEditHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handle(UserEditDto $userEditDto, User $user)
    {
        $user->setFirstName($userEditDto->firstName);
        $user->setLastName($userEditDto->lastName);
        $user->setPhoneNumber($userEditDto->phoneNumber);
        $user->setBirthDate($userEditDto->birthDate);
        $userAddress = $user->getAddress() ?? new Address();
        $userAddress->setAddress($userEditDto->address->address);
        $userAddress->setZipCode($userEditDto->address->zipCode);
        $userAddress->setCity($userEditDto->address->city);
        $user->setAddress($userAddress);

        // TODO IMAGE

        $this->entityManager->flush();
    }
}