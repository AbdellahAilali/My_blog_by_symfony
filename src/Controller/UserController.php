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
     * @Route("/users/{id}", name="blog_users", defaults={"id" = 1})
     */

    public function UserAction($id)
    {

        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['id'=>$id]);


        $result['id'] = $user->getId();
        $result['first_name'] = $user->getFirstname();
        $result['last_name'] = $user->getLastname();
        $result['date_of_birth'] = $user->getDateNaissance()->format('d/m/Y');
        $tabComm = [];


        foreach ( $user->getComments() as $comment){

            $tabComm[] = [

                $result['description'] = $comment->getDescription(),
                $result['title'] =$comment->getTitle()
            ];

        }

        $result['comments'] = $tabComm;

        return new JsonResponse($result);


    }


}


