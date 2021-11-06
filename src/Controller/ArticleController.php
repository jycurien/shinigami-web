<?php
namespace App\Controller;


use App\Dto\ArticleDto;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Handler\Article\ArticleHandler;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class ArticleController extends AbstractController
{
    /**
     * @Route({
     *     "en": "/news",
     *     "fr": "/actualites"
     *      },
     *     name="article_articles",
     *     methods={"GET"})
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function articles(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAllArticlesOrdered();

        return $this->render("article/articles.html.twig", [
            "articles" => $articles
        ]);
    }

    /**
     * @Route({
     *     "en": "/news/{slug}_{id<\d+>}",
     *     "fr": "/actualites/{slug}_{id<\d+>}"
     *      },
     *     name="article_article",
     *     methods={"GET"})
     * @param Article $article
     * @return Response
     */
    public function article(Article $article): Response
    {
        return $this->render("article/article.html.twig", [
            "article" => $article
        ]);
    }

    /**
     * @Route("/admin/articles",
     *     name="articles_admin",
     *     methods={"GET"})
     * @Security("user.isValidateContract() and is_granted('ROLE_ADMIN')")
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function articlesAdmin(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAllArticlesOrdered();

        return $this->render('admin/article/articles.html.twig', [
            "articles" => $articles
        ]);
    }

    /**
     * @Route({
     *     "en": "/admin/create-article",
     *     "fr": "/admin/creer-un-article"
     *      },
     *     name="create_article_admin",
     *     methods={"GET", "POST"})
     * @Security("user.isValidateContract() and is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @param ArticleHandler $articleHandler
     * @return Response
     * @throws \Exception
     */
    public function createArticle(Request $request, ArticleHandler $articleHandler)
    {
        $articleDto = new ArticleDto();

        $form = $this->createForm(ArticleType::class, $articleDto)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Traitement de l'article.
            $article = $articleHandler->handle($articleDto);

            if (null !== $article) {
                $this->addFlash('notice', 'article.add.ok');
                return $this->redirectToRoute('articles_admin');
            } else {
                $this->addFlash('error', 'article.add.error');
            }
        }

        return $this->renderForm('admin/article/update_article.html.twig', [
            'form' => $form,
            'pageTitle' => 'admin.article.add'
        ]);
    }


    /**
     * @Route({
     *     "en": "/admin/remove-article/{id<\d+>}",
     *     "fr": "/admin/supprimer-un-article/{id<\d+>}"
     *      },
     *     name="remove_article_admin",
     *     methods={"GET"})
     * @Security("user.isValidateContract() and is_granted('ROLE_ADMIN')")
     * @param Article $article
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function removeArticle(Article $article, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($article);
        $entityManager->flush();

        $this->addFlash('success', "article.delete.ok");
        return $this->redirectToRoute('articles_admin');
    }

    /**
     * @Route({
     *     "en": "/admin/update-article/{id<\d+>}",
     *     "fr": "/admin/modifier-un-article/{id<\d+>}"
     *      },
     *     name="update_article_admin",
     *     methods={"GET", "POST"})
     * @Security("user.isValidateContract() and is_granted('ROLE_ADMIN')")
     * @param Article $article
     * @param Request $request
     * @param ArticleHandler $updateHandler
     * @return Response
     */
    public function updateArticle(Article $article, Request $request, ArticleHandler $updateHandler)
    {
        $articleDto = new ArticleDto($article->getTitle(), $article->getContent(), $article->getPicture(), $article->isSlider());

        $options = [
            'picture_url' => $this->getParameter('web_url') . '/' . $this->getParameter('articles_dir') . $article->getPicture()
        ];
        $form = $this->createForm(ArticleType::class, $articleDto, $options)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $updateHandler->handle($articleDto, $article);

            if (null !== $article) {
                $this->addFlash('notice', 'article.update.ok');
                return $this->redirectToRoute('articles_admin');
            } else {
                $this->addFlash('error', 'article.add.error');
            }
        }

        return $this->render('admin/article/update_article.html.twig', [
            'form' => $form->createView(),
            'pageTitle' => 'admin.article.update'
        ]);
    }
}