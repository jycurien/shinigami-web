<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 10/12/2018
 * Time: 11:55
 */

namespace App\Service;


class CheckSumCalculator
{
    /**
     * @param int $codeCenter
     * @param int $codeCard
     * @return int
     */
    public function calculate(int $codeCenter, int $codeCard): int
    {
        $arrayCodeCenter  = array_map('intval', str_split($codeCenter));
        $arrayCodeCard  = array_map('intval', str_split($codeCard));

        $arraySum = array_merge($arrayCodeCenter, $arrayCodeCard);

        return array_sum($arraySum) % 9;
    }
}