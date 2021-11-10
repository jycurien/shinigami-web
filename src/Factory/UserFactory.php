<?php


namespace App\Factory;


use App\Dto\EmployeeDto;
use App\Dto\UserRegistrationDto;
use App\Entity\Contract;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactory
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function createFromRegistrationDto(UserRegistrationDto $userDto): User
    {
        $user = new User();
        $user->setUsername($userDto->username);
        $user->setEmail($userDto->email);
        $user->setPassword($this->passwordHasher->hashPassword($user, $userDto->password));

        return $user;
    }

    public function createFromEmployeeDto(EmployeeDto $employeeDto): User
    {
        $user = $this->createFromRegistrationDto(new UserRegistrationDto($employeeDto->username, $employeeDto->email, 'password'));
        $user->setEnabled(true);
        $user->setFirstName($employeeDto->firstName);
        $user->setLastName($employeeDto->lastName);
        $user->setRoles($employeeDto->roles);
        $contract = new Contract();
        $contract->setCenter($employeeDto->contract->center);
        $contract->setStartDate($employeeDto->contract->startDate);
        $contract->setEndDate($employeeDto->contract->endDate);
        $user->setContract($contract);
        return $user;
    }
}