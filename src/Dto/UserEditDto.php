<?php


namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\User;

class UserEditDto
{
    /**
     * @var null|string
     * @Assert\Length(
     *      min = 2,
     *      max = 255
     * )
     */
    public $firstName;
    /**
     * @var null|string
     * @Assert\Length(
     *      min = 2,
     *      max = 255
     * )
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
    public $image;
    public $address;

    public function __construct(User $user)
    {
        $this->firstName = $user->getFirstName();
        $this->lastName = $user->getLastName();
        $this->phoneNumber = $user->getPhoneNumber();
        $this->birthDate = $user->getBirthDate();
        $this->image = $user->getImage();
        if (null !== $user->getAddress()) {
            $this->address = new AddressDto(
                $user->getAddress()->getAddress(),
                $user->getAddress()->getZipCode(),
                $user->getAddress()->getCity()
            );
        } else {
            $this->address = new AddressDto();
        }
    }
}