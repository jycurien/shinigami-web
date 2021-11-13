<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserPlayGame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserPlayGame|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserPlayGame|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserPlayGame[]    findAll()
 * @method UserPlayGame[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPlayGameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPlayGame::class);
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function findLatestUserPlayGamesByUser(User $user)
    {
        return $this->createQueryBuilder('upg')
            ->where('upg.user = :user')
            ->setParameter('user', $user)
            ->innerJoin('upg.game', 'g')
            ->addSelect('g')
            ->orderBy('g.date', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param User $user
     * @return mixed
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function findMaxScoreByUser(User $user)
    {
        return $this->createQueryBuilder('upg')
            ->select('MAX(upg.score)')
            ->where('upg.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param User $user
     * @return mixed
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function findSingleVictoriesByUser(User $user)
    {
        return $this->createQueryBuilder('upg')
            ->select('COUNT(upg.id)')
            ->where('upg.user = :user')
            ->andWhere('NOT EXISTS (SELECT upg2 FROM App\Entity\UserPlayGame upg2 WHERE upg2.game = upg.game AND upg2.score > upg.score)')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
