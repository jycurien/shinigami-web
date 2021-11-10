<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @param string $cardNumber
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function findByCardNumber(string $cardNumber)
    {
        return $this->createQueryBuilder('c')
            ->where('c.cardNumbers LIKE :number')
            ->setParameter('number', "%:\"$cardNumber\"%")
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param string $username
     * @return bool
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function isUsernameUnique(string $username):bool
    {
        return (0 === $this->createQueryBuilder('u')
                ->select('COUNT(u.id)')
                ->where('u.username = :username')
                ->setParameter('username', $username)
                ->getQuery()
                ->getSingleScalarResult());
    }

    /**
     * @param string $email
     * @return bool
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function isEmailUnique(string $email):bool
    {
        return (0 === $this->createQueryBuilder('u')
                ->select('COUNT(u.id)')
                ->where('u.email = :email')
                ->setParameter('email', $email)
                ->getQuery()
                ->getSingleScalarResult());
    }
}
