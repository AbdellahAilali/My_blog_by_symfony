<?php

namespace App\Manager;

use App\Controller\FormController;
use App\Entity\UserAccount;
use App\Entity\UserRegistration;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Integer;

class FormRegistrationManager
{
    /**
     * @var EntityManagerInterface
     */
    public $entityManager;
    /**
     * @var FormController
     */
    public $controller;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $civility
     * @param string $pseudo
     * @param string $lastName
     * @param string $firstName
     * @param string $street
     * @param string $city
     * @param int $postalCode
     * @param int $phoneNumber
     * @param string $email
     * @param string $password
     * @param string|null $complement
     */
    public function createUser(
        int $civility,
        string $pseudo,
        string $lastName,
        string $firstName,
        string $street,
        string $city,
        int $postalCode,
        int $phoneNumber,
        string $email,
        string $password,
        string $complement = null
    )
    {
        $user = new UserRegistration(
            $civility,
            $pseudo,
            $lastName,
            $firstName,
            $street,
            $city,
            $postalCode,
            $phoneNumber,
            $email,
            $password,
            $complement);

        $this->entityManager->persist($user);

        $this->entityManager->flush();
    }
}