<?php


namespace App\Dto;

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
     * @param $address
     * @param $zipCode
     * @param $city
     */
    public function __construct(?string $address = null, ?int $zipCode = null, ?string $city = null)
    {
        $this->address = $address;
        $this->zipCode = $zipCode;
        $this->city = $city;
    }
}