<?php


namespace App\Dto;


use App\Entity\Contract;
use App\Entity\User;
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
     * @AppAssert\UniqueFieldConstraint(entityRepository="App\Repository\UserRepository", groups={"create"})
     */
    public $username;
    /**
     * @var null|string
     * @Assert\NotBlank()
     * @Assert\Email()
     * @AppAssert\UniqueFieldConstraint(entityRepository="App\Repository\UserRepository", groups={"create"})
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
    /**
     * @var null|string
     * @Assert\Length(
     *      min = 2,
     *      max = 255
     * )
     */
    public $phoneNumber;
    /**
     * @var \DateTimeInterface|null
     * @Assert\Type(type="\Datetime")
     */
    public $birthDate;

    /**
     * Must be a square
     * @Assert\Image(
     *     minWidth = 225,
     *     minHeight = 225,
     * )
     */
    public $image;
    /**
     * @var null|AddressDto
     * @Assert\Valid() // USE THIS TO VALIDATE EMBEDDED FORM
     */
    public $address;

    public function __construct(?User $user = null)
    {
        if (null !== $user) {
            $this->roles = $user->getRoles();
            $this->username = $user->getUsername();
            $this->email = $user->getEmail();
            $this->contract = new ContractDto($user->getContract());
            $this->firstName = $user->getFirstName();
            $this->lastName = $user->getLastName();
            $this->phoneNumber = $user->getPhoneNumber();
            $this->birthDate = $user->getBirthDate();
            $this->image = $user->getImage();
            $this->address = new AddressDto($user->getAddress());
        } else {
            $this->contract = new ContractDto();
            $this->address = new AddressDto();
        }
    }
}