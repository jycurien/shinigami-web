<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 20/12/2018
 * Time: 11:11
 */

namespace App\Controller;


use Behat\Transliterator\Transliterator;

trait SluggableTrait
{
    /**
     * Generate slug from string
     *
     * @param string $text
     * @return string
     */
    public function slugify(string $text){
        return Transliterator::transliterate($text);
    }
}