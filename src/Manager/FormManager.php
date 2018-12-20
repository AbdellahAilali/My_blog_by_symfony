<?php

namespace App\Manager;

use App\Controller\FormController;
use App\Entity\UserTest;
use Doctrine\ORM\EntityManagerInterface;

class FormManager
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
     * @param FormController $controller
     */
    public function __construct(
        EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createUser($username,  $pseudo)
    {
        $user = new UserTest($username,$pseudo);

        $this->entityManager->persist($user);

        $this->entityManager->flush();
    }
}