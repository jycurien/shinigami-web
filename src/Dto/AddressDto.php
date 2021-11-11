<?php


namespace App\Dto;

use App\Entity\Address;
use Symfony\Component\Validator\Constraints as Assert;

class AddressDto
{
    /**
     * @var null|string
     * @Assert\Length(
     *      min = 2,
     *      max = 255
     * )
     */
    public $address;
    /**
     * @var null|string
     * @Assert\Type(type="integer")
     * @Assert\Positive()
     */
    public $zipCode;
    /**
     * @var null|string
     * @Assert\Length(
     *      min = 2,
     *      max = 255
     * )
     */
    public $city;

    /**
     * AddressDto constructor.
     * @param Address|null $address
     */
    public function __construct(?Address $address = null)
    {
        if (null !== $address) {
            $this->address = $address->getAddress();
            $this->zipCode = $address->getZipCode();
            $this->city = $address->getCity();
        }
    }
}