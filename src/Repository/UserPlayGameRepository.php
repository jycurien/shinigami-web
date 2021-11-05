<?php

namespace App\Repository;

use App\Entity\UserPlayGame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    // /**
    //  * @return UserPlayGame[] Returns an array of UserPlayGame objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserPlayGame
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
