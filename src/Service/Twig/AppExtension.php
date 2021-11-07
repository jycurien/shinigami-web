<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 10/12/2018
 * Time: 12:08
 */

namespace App\Service\Twig;


//use App\Service\CheckSumCalculator;
use App\Service\CheckSumCalculator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    const NB_SUMMARY_CHAR = 150;

    private $checkSumCalculator;

    /**
     * AppExtension constructor.
     * @param $checkSumCalculator
     */
    public function __construct(CheckSumCalculator $checkSumCalculator)
    {
        $this->checkSumCalculator = $checkSumCalculator;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('checkSum', array($this, 'calculateCheck')),
            new TwigFunction('cardNumber', array($this, 'formatCardNumber')),
        ];
    }

    /**
     * @param int $codeCenter
     * @param int $codeCard
     * @return int
     */
    public function calculateCheck(int $codeCenter, int $codeCard): int
    {
        return $this->checkSumCalculator->calculate($codeCenter, $codeCard);
    }

    /**
     * @param int $codeCenter
     * @param int $codeCard
     * @return string
     */
    public function formatCardNumber(int $codeCenter, int $codeCard): string
    {
        return str_pad($codeCenter, 3, "0", STR_PAD_LEFT).' '
            .str_pad($codeCard, 6, "0", STR_PAD_LEFT).' '.$this->checkSumCalculator->calculate($codeCenter, $codeCard);
    }

    public function getFilters()
    {
        return [
            new TwigFilter('summary', function($text) {
                $text = strip_tags($text);

                if (strlen($text) > self::NB_SUMMARY_CHAR) {
                    $text = wordwrap($text, self::NB_SUMMARY_CHAR);
                    $text = explode("\n", $text);
                    $text = $text[0] . '...';
                }

                return $text;
            }, ['is_safe' => ['html']])
        ];
    }
}