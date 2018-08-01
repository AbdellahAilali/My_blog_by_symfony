<?php

namespace App\Manager;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class UserManager
 * @package App\Manager
 */
class UserManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string             $id
     * @param string             $firstName
     * @param string             $lastName
     * @param \DateTimeInterface $birthDay
     */
    public function createUser(string $id, string $firstName, string $lastName, \DateTimeInterface $birthDay)
    {
        $user = new User($id, $firstName, $lastName, $birthDay);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
