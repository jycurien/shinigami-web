<?php


namespace App\Tests\Service;


use App\Service\CheckSumCalculator;
use PHPUnit\Framework\TestCase;

class CheckSumCalculatorTest extends  TestCase
{
    private $checkSumCalculator;

    public function setUp(): void
    {
        $this->checkSumCalculator = new CheckSumCalculator();
    }

    public function testCalculate()
    {
        for ($i = 0; $i < 9; $i++) {
            $result = $this->checkSumCalculator->calculate(124, 100001+$i);
            $this->assertEquals($i, $result);
            $this->assertIsInt($result);
        }
    }
}