<?php

namespace App\Repository;

use App\Entity\Contract;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Contract|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contract|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contract[]    findAll()
 * @method Contract[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContractRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contract::class);
    }

    public function findContractsWithUserAndCenter()
    {
        return $this->createQueryBuilder('c')
            ->join('c.center', 'ce')
            ->addSelect('ce')
            ->join('c.user', 'u')
            ->addSelect('u')
            ->orderBy('c.startDate', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }
}
