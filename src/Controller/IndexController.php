<?php
namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\PricingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        $sliderArticles = $articleRepository->findSliderArticles();
        $latestArticles = $articleRepository->findLatestArticles();

        return $this->render("index/index.html.twig", [
            "sliderArticles" => $sliderArticles,
            "latestArticles" => $latestArticles
        ]);
    }

    /**
     * @Route({
     *     "en": "/the-concept",
     *     "fr": "/le-concept"
     *      },
     *     name="index_concept",
     *     methods={"GET"})
     * @param PricingRepository $pricingRepository
     * @return Response
     */
    public function concept(PricingRepository $pricingRepository): Response
    {
        $pricings = $pricingRepository->findAll();

        return $this->render('index/concept.html.twig', [
            'pricings' => $pricings
        ]);
    }

}