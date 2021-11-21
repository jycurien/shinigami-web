<?php


namespace App\Tests\Service;


use App\Service\QrCodeGenerator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class QrCodeGeneratorTest extends KernelTestCase
{
    private $qrCodeGenerator, $filesystem;

    public function setUp(): void
    {
        $this->qrCodeGenerator = new QrCodeGenerator('./tests/qrCodeTestDirectory/');
        self::bootKernel();
        $this->filesystem = static::getContainer()->get('filesystem');
        if ($this->filesystem->exists('./tests/qrCodeTestDirectory/123.png')) {
            $this->filesystem->remove('./tests/qrCodeTestDirectory/123.png');
        }
    }

    public function testGenerateQrCodeThatDoesntExists()
    {
        $this->qrCodeGenerator->generate(123);
        $this->assertTrue($this->filesystem->exists('./tests/qrCodeTestDirectory/123.png'));
    }
}