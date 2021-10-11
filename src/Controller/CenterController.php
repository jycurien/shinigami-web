<?php

namespace App\Controller;

use App\Entity\Center;
use App\Repository\CenterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CenterController extends AbstractController
{
    /**
     * @Route({
     *     "en": "/centers",
     *     "fr": "/centres"
     *      },
     *     name="center_centers",
     *     methods={"GET"})
     * @param CenterRepository $centerRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function centers(CenterRepository $centerRepository)
    {
        $centers = $centerRepository->findAll();
        return $this->render("center/centers.html.twig", [
            'centers' => $centers,
        ]);
    }
}