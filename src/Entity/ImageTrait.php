<?php

namespace App\Entity;


trait ImageTrait
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    private $imageTmp;

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }
}