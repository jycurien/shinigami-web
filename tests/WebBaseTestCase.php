<?php


namespace App\Tests;


use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\PantherTestCase;

class WebBaseTestCase extends PantherTestCase
{
    protected $arg;

    public function setUp(): void
    {
        $_SERVER['PANTHER_NO_HEADLESS'] = 1;
        $this->arg = ['window-size=1280,800', 'disable-infobars'];
    }

    protected function scrollDown(Client $client, string $element = null)
    {
        if($element) {
            $size = $client->getCrawler()->filter($element)->getLocation()->getY()-100;
        } else {
            $size = $client->getCrawler()->getSize()->getHeight()-400;
        }

        for($i=0; $i<$size; $i+=2) {
            $client->executeScript('window.scroll(0,'.$i.')');
        }
    }
}