<?php


namespace App\Dto;


use App\Entity\Center;

class ContractDto
{
    public $startDate;
    public $endDate;
    public $center;

    public function __construct(?\DateTimeInterface $startDate = null, ?\DateTimeInterface $endDate = null, ?Center $center = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->center = $center;
    }
}