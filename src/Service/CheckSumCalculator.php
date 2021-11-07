<?php


namespace App\Service;


class CheckSumCalculator
{
    /**
     * @param int $centerCode
     * @param int $cardCode
     * @return int
     */
    public function calculate(int $centerCode, int $cardCode): int
    {
        $arrayCenterCode  = array_map('intval', str_split($centerCode));
        $arrayCardCode  = array_map('intval', str_split($cardCode));

        $arraySum = array_merge($arrayCenterCode, $arrayCardCode);

        return array_sum($arraySum) % 9;
    }
}