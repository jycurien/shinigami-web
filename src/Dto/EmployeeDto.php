<?php


namespace App\Dto;


use App\Entity\Contract;
use Doctrine\Common\Collections\ArrayCollection;

class EmployeeDto
{
    public $roles;
    public $username;
    public $email;
    public $contract;
    public $firstName;
    public $lastName;

    public function __construct(?array $roles = [],
                                ?string $username = null,
                                ?string $email = null,
                                ?Contract $contract = null,
                                ?string $firstName = null,
                                ?string $lastName = null)
    {
        $this->roles = $roles;
        $this->username = $username;
        $this->email = $email;
        $this->contract = new ContractDto($contract?->getStartDate(), $contract?->getEndDate(), $contract?->getCenter());
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }
}