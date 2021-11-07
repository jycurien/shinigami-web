<?php


namespace App\Handler\Card;


class CardHandler
{
    public function formatCardNumber($card)
    {
        return $card->centerCode.$card->cardCode.$card->checkSum;
    }
}