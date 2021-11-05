<?php

namespace App\Repository;

use App\Entity\Center;
use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Room|null find($id, $lockMode = null, $lockVersion = null)
 * @method Room|null findOneBy(array $criteria, array $orderBy = null)
 * @method Room[]    findAll()
 * @method Room[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    public function countRoomsByCenter(Center $center)
    {
        return $this->createQueryBuilder('room')
            ->select('count(room.id)')
//            ->join('room.center', 'center')
//            ->join('center.contracts', 'contracts')
            ->andWhere('room.center = :center')
            ->setParameter('center', $center)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }
}
