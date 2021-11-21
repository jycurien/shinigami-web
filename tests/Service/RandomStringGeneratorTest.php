<?php


namespace App\Tests\Service;


use App\Service\RandomStringGenerator;
use PHPUnit\Framework\TestCase;

class RandomStringGeneratorTest extends TestCase
{
    public function testGetRandomAlphaNumStr()
    {
        $randomStringGenerator = new RandomStringGenerator();
        $string = $randomStringGenerator->getRandomAlphaNumStr(30);
        $this->assertIsString($string);
        $this->assertEquals(30, strlen($string));
    }
}