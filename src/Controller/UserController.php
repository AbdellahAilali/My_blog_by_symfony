<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


class UserController
{
    private $database;

    public function __construct(EntityManagerInterface $database)
    {
        $this->database = $database;
    }


    /**
     * @Route("/user/{id}", name="blog")
     */

    public function loadUserAction($id)
    {
        $users = $this->database->getRepository(User::class)
            ->findOneBy(["id" => $id]);


        if (empty($users)) {
            return new JsonResponse(null, 404);
        }

        $resultat["lastname"] = $users->getLastname();
        $resultat["firstname"] = $users->getFirstname();
        $tabComments = [];
        $resultat['comments'] = $tabComments;

        foreach ($users->getComments() as $user) {
            $tabComments[] = [

                "title" => $user->getTitle(),
                "description" => $user->getDescription()
            ];
        }

        return new JsonResponse($resultat);


    }


}