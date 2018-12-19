<?php

namespace App\Repository;

use App\Entity\Usertest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Usertest|null find($id, $lockMode = null, $lockVersion = null)
 * @method Usertest|null findOneBy(array $criteria, array $orderBy = null)
 * @method Usertest[]    findAll()
 * @method Usertest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsertestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Usertest::class);
    }

    // /**
    //  * @return Usertest[] Returns an array of Usertest objects
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
    public function findOneBySomeField($value): ?Usertest
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
