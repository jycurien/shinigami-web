<?php

namespace App\Repository;

use App\Entity\Center;
use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function findLatestGamesWithCenter()
    {
        return $this->createQueryBuilder('g')
            ->orderBy('g.date', 'DESC')
            ->setMaxResults(250)
            ->join('g.room', 'r')
            ->addSelect('r')
            ->leftJoin('g.userPlayGames', 'upg')
            ->addSelect('upg')
            ->addOrderBy('upg.score', 'DESC')
            ->join('upg.user', 'u')
            ->addSelect('u')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param Center $center
     * @param string $status
     * @return mixed
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countGamesByCenterAndStatus(Center $center, string $status)
    {
        return $this->createQueryBuilder('g')
            ->select('count(g.id)')
            ->join('g.room', 'r')
            ->andWhere('r.center = :center')
            ->setParameter('center', $center)
            ->andWhere('g.status = :status')
            ->setParameter('status', $status)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }
}
