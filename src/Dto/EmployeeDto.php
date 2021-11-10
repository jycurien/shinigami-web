<?php


namespace App\Dto;


use App\Entity\Contract;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as AppAssert;


class EmployeeDto
{
    /**
     * @var array|null
     * @Assert\NotBlank()
     */
    public $roles;
    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 255
     * )
     * @AppAssert\UniqueFieldConstraint(entityRepository="App\Repository\UserRepository")
     */
    public $username;
    /**
     * @var null|string
     * @Assert\NotBlank()
     * @Assert\Email()
     * @AppAssert\UniqueFieldConstraint(entityRepository="App\Repository\UserRepository")
     */
    public $email;
    /**
     * @var ContractDto
     * @Assert\Valid() // USE THIS TO VALIDATE EMBEDDED FORM
     */
    public $contract;
    /**
     * @var string|null
     * @Assert\NotBlank()
     */
    public $firstName;
    /**
     * @var string|null
     * @Assert\NotBlank()
     */
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