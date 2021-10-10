<?php
namespace App\Controller;

//use App\Entity\Article;
//use App\Entity\Pricing;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param ArticleRepository $articleRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(ArticleRepository $articleRepository)
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
     */
    public function concept()
    {
        dd('TODO');
//        $pricings = $this->getDoctrine()->getRepository(Pricing::class)->findAll();
//
//        return $this->render('index/concept.html.twig', [
//            'pricings' => $pricings
//        ]);
    }

}