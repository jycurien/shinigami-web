<?php


namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ArticleDto
{
    /**
     * @Assert\NotBlank(message="Veuillez saisir un titre")
     * @Assert\Length(
     *     max="255",
     *     maxMessage="Attention, pas plus de 255 caractères."
     * )
     */
    public $title;
    public $content;
    /**
     * @Assert\Image(
     *     mimeTypesMessage="Vérifiez le format de votre image",
     *     maxSize="2M",
     *     maxSizeMessage="Votre image est trop lourde, maximum 2Mo"
     * )
     */
    public $picture;
    public $slider;

    /**
     * ArticleDto constructor.
     * @param $title
     * @param $content
     * @param $picture
     * @param bool $slider
     */
    public function __construct(?string $title = null, ?string $content = null, ?string $picture  = null, bool $slider = false)
    {
        $this->title = $title;
        $this->content = $content;
        $this->picture = $picture;
        $this->slider = $slider;
    }
}