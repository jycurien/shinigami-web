<?php


namespace App\Tests;


use Symfony\Component\Panther\Client;

class FrontOfficeTest extends WebBaseTestCase
{
    public function testPublicPages()
    {
        $client = Client::createChromeClient(null, $this->arg);
        $crawler = $client->request('GET', 'https://127.0.0.1:8000');
        $client->waitFor('.hide-button');
        sleep(2);

//        $client->executeScript('
//            const synth = window.speechSynthesis;
//            let utterThis = new SpeechSynthesisUtterance(\'Bonjour je suis Madame Shinigami\');
//            synth.speak(utterThis);
//            utterThis.text=\'Je vais vous présenter le projet Shinigami Laser\';
//            synth.speak(utterThis);
//        ');
//


        sleep(2);

        $crawler->filter('.carousel-control-next')->click();
        sleep(2);

        $crawler->filter('.carousel-control-next')->click();
        sleep(2);

        sleep(15);
        $this->scrollDown($client);

        // Navigate to concept page
        $link = $crawler->selectLink('Le Concept')->link();
        $crawler = $client->click($link);

        $this->assertSame('Le Concept', $crawler->filter('h1')->text());

        sleep(15);
        $this->scrollDown($client);

        // Navigate to centers page
        $link = $crawler->selectLink('Nos Centres')->link();
        $crawler = $client->click($link);

        $this->assertSame('Nos Centres', $crawler->filter('h1')->text());

        sleep(15);
        $this->scrollDown($client);

        // Navigate to news
        $link = $crawler->selectLink('Actualités')->link();
        $crawler = $client->click($link);

        $this->assertSame('Actualités', $crawler->filter('h1')->text());

        sleep(15);
        $this->scrollDown($client);
    }
}