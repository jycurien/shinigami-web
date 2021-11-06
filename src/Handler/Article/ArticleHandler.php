<?php


namespace App\Handler\Article;


use App\Dto\ArticleDto;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleHandler
{
    /**
     * @var SluggerInterface
     */
    private $slugger;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var TokenInterface
     */
    private $token;
    /**
     * @var string
     */
    private $articleAssetsDir;

    public function __construct(SluggerInterface $slugger, FlashBagInterface $flashBag, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage, string $articleAssetsDir)
    {
        $this->slugger = $slugger;
        $this->flashBag = $flashBag;
        $this->entityManager = $entityManager;
        $this->token = $tokenStorage->getToken();
        $this->articleAssetsDir = $articleAssetsDir;
    }

    /**
     * @param ArticleDto $articleDto
     * @param Article|null $article
     * @return Article
     */
    public function handle(ArticleDto $articleDto, ?Article $article = null): Article
    {
        if (null === $article) {
            $article = new Article();
        }

        // Traitement de l'upload de l'image
        /** @var UploadedFile $picture */
        $picture = $articleDto->picture;

        if (null !== $picture) {
            $fileName = $this->slugger->slug($articleDto->title)
                . '.' . $picture->guessExtension();

            try {
                $picture->move(
                    $this->articleAssetsDir,
                    $fileName
                );
            } catch (FileException $e) {
                $this->flashBag->add('error', $e->getMessage());
            }

            $article->setPicture($fileName);
        }

        $article->setTitle($articleDto->title)
                ->setSlug($this->slugger->slug($articleDto->title))
                ->setContent($articleDto->content)
                ->setSlider($articleDto->slider)
                ->setAuthor($this->token->getUser());

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        return $article;
    }
}