<?php


namespace App\Dto;


class ArticleDto
{
    public $title;
    public $content;
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