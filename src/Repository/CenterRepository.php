<?php

namespace App\Repository;

use App\Entity\Address;
use App\Entity\Center;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Center|null find($id, $lockMode = null, $lockVersion = null)
 * @method Center|null findOneBy(array $criteria, array $orderBy = null)
 * @method Center[]    findAll()
 * @method Center[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CenterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Center::class);
    }

    public function findAllWithAddress()
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.address', 'a')
            ->addSelect('a')
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Center
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
