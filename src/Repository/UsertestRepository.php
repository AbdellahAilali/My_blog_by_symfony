<?php

namespace App\Repository;

use App\Entity\UserTest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserTest|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserTest|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserTest[]    findAll()
 * @method UserTest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsertestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserTest::class);
    }

    // /**
    //  * @return UserTest[] Returns an array of UserTest objects
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
    public function findOneBySomeField($value): ?UserTest
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
