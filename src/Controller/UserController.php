<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     *
     * @Route("/users", name="blog_users")
     */

    public function UserAction()
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();

        return new JsonResponse($users);

    }

}


