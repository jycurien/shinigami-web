<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    const MAX_SLIDER_ARTICLES = 3;
    const MAX_LATEST_ARTICLES = 3;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public static function createIsSliderCriteria(): Criteria
    {
        return Criteria::create()
            ->andWhere(Criteria::expr()->eq('slider', true));
    }

    public function findSliderArticles()
    {
        return $this->createQueryBuilder('a')
            ->addCriteria(self::createIsSliderCriteria())
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults(self::MAX_SLIDER_ARTICLES)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findLatestArticles()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults(self::MAX_LATEST_ARTICLES)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllArticlesOrdered()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }
}
