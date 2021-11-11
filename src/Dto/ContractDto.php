<?php


namespace App\Dto;


use App\Entity\Center;
use App\Entity\Contract;
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

    /**
     * ContractDto constructor.
     * @param Contract|null $contract
     */
    public function __construct(?Contract $contract = null)
    {
        if (null !== $contract) {
            $this->startDate = $contract->getStartDate();
            $this->endDate = $contract->getEndDate();
            $this->center = $contract->getCenter();
        }
    }
}