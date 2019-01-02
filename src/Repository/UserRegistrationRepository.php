<?php

namespace App\Repository;

use App\Entity\UserRegistration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserRegistration|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserRegistration|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserRegistration[]    findAll()
 * @method UserRegistration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRegistrationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserRegistration::class);
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
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
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /*public function usersAndDescription()
    {
        return $this->createQueryBuilder('u')
            ->getQuery()
            ->getResult();

     /////->join('c.comment, com')
            ->addSelect('com');/////

    }*/




}
