<?php


namespace App\Dto;


use App\Entity\Center;
use Symfony\Component\Validator\Constraints as Assert;


class ContractDto
{
    /**
     * @var \DateTimeInterface|null
     * @Assert\NotBlank()
     * @Assert\Type(type="\Datetime")
     */
    public $startDate;
    /**
     * @var \DateTimeInterface|null
     * @Assert\Type(type="\Datetime")
     * @Assert\GreaterThanOrEqual(propertyPath="startDate")
     */
    public $endDate;
    /**
     * @var Center|null
     * @Assert\NotBlank()
     */
    public $center;

    public function __construct(?\DateTimeInterface $startDate = null, ?\DateTimeInterface $endDate = null, ?Center $center = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->center = $center;
    }
}