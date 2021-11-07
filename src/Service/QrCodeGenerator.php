<?php


namespace App\Service;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeGenerator
{
    /**
     * @var string
     */
    private $qrCodesImageDirectory;

    public function __construct(string $qrCodesImageDirectory)
    {
        $this->qrCodesImageDirectory = $qrCodesImageDirectory;
    }

    public function generate(int $number)
    {
        $writer = new PngWriter();
        $qrCode = QrCode::create($number)->setSize(200)->setMargin(0);
        $writer->write($qrCode)->saveToFile($this->qrCodesImageDirectory.$number.'.png');
    }
}