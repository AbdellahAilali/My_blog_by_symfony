<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController
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
     * @Route ("/user/{id}", name="load_user", methods={"GET"})
     *
     * @param string $id
     *
     * @return JsonResponse
     */
    public function loadUserAction($id)
    {
        /** @var User $user */
        $user = $this->entityManager
            ->getRepository(User::class)
            ->find($id);

        if (empty($user)) {
            return new JsonResponse(null, 404);
        }

        $result = [];
        $result["firstname"] = $user->getFirstname();
        $result["getLastname"] = $user->getLastname();

        $tabComments = [];
        foreach ($user->getComments() as $comment) {
            $tabComments[] = [
                "title" => $comment->getTitle(),
                "comment" => $comment->getDescription()
            ];
        }
        $result["comments"] = $tabComments;
        return new JsonResponse($result);
    }


    /**
     * @Route ("/user/{lastname}", name="delete_user", methods={"DELETE"})
     * @param $lastName
     * @return JsonResponse
     */
    public function deleteUserAction($id)
    {
        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)
            ->find($id);

        if (empty($user)) {
            return new JsonResponse("no", 404);
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return new JsonResponse("ok", 200);
    }


    /**
     * @Route ("/user/", name="create_user", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */

    public function createUserAction(Request $request)
    {
        /** @var Request $request */

        $resultJson = $request->getContent();

        $result = json_decode($resultJson);

        //utilisationde la class stdClass pour avoir
        // acces au valeurs  decode

        $lastname = $result->lastname;
        $firstname = $result->firstname;
        $date = new \DateTime($result->dateNaissance);

        $newUser = new User();
        $newUser->setLastname($lastname);
        $newUser->setFirstname($firstname);
        $newUser->setDateNaissance($date);

        $em = $this->entityManager;

        $em->persist($newUser);

        $em->flush();

        return new JsonResponse();

    }

    /**
     * @Route("/user/modify/{id}", name="modify_user",methods={"PUT"})
     * @param Request $request
     * @return JsonResponse
     */

    public function modifyUserAction(Request $request)
    {
        /** @var User $user */
        /** @var Request $request */
        $user = $this->entityManager
            ->getRepository(User::class)
            ->find($id ="cd72f69f-ae27-4257-bd0c-1aeff64b6f60");
        $resultJson = $request->getContent();

        $result = json_decode($resultJson);

        $user->setLastname($result->lastname);
        $user->setFirstname($result->firstname);
        $user->setDateNaissance(new \DateTime($result->dateNaissance));

        $em = $this->entityManager;

        $em->persist($user);

        $em->flush();

        return new JsonResponse();

    }


}