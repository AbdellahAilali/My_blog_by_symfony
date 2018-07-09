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
        $response = $this->database->getRepository(User::class)
            ->findOneBy(["id" => $id]);

        $resultat["lastname"] = $response->getLastname();
        $resultat["firstname"] = $response->getFirstname();
        $tabComments=[];

        foreach ($response->getComments() as $comment) {
            $tabComments[] = [

                "title" => $comment->getTitle(),
                "description" =>$comment->getDescription()
            ];
        }



        $resultat['comments'] = $tabComments;
        return new JsonResponse($resultat);
    }


}