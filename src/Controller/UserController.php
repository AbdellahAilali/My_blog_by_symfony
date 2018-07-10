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
     * @Route ("/user/{id}", name="blog")
     *
     * @param $id
     * @return JsonResponse
     */
    public function loadUserAction($id)
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(["id" => $id]);
        $resultat = [];

        $resultat["firstname"] = $user->getFirstname();
        $resultat["getLastname"] = $user->getLastname();
        $tabComments=[];
        foreach ($user->getComments() as $comment) {
            $tabComments[] = [

                "title" => $comment->getTitle(),
                "comment" => $comment->getDescription()
            ];
        }

        $resultat["comments"] = $tabComments;
        return new JsonResponse($resultat);


    }

}