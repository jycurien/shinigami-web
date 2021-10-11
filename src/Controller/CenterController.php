<?php

namespace App\Controller;

use App\Repository\CenterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
     * @return Response
     */
    public function centers(CenterRepository $centerRepository): Response
    {
        $centers = $centerRepository->findAll();
        return $this->render("center/centers.html.twig", [
            'centers' => $centers,
        ]);
    }
}