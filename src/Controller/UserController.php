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
     * @Route ("/user/{lastName}", name="load_user", methods={"GET"})
     *
     * @param string $lastName
     *
     * @return JsonResponse
     */
    public function loadUserAction($lastName)
    {
        /** @var User $user */
        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(["lastname" => $lastName]);

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
    public function deleteUser($lastName)
    {
        $user = $this->entityManager->getRepository(User::class)
            ->findOneBy(["lastname" => $lastName]);


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
        $resultJson = $request->getContent();

        $result = json_decode($resultJson);

        //utilisationde la class stdClass pour avoir
        // acces au valeurs  decode

        $lastName = $result->lastname;
        $firstName = $result->firstname;
        $date = new \DateTime($result->dateNaissance);

        $newUser = new User();

        $newUser->setFirstname($lastName)->setLastname($firstName)
            ->setDateNaissance($date);

        $em = $this->entityManager;

        $em->persist($newUser);

        $em->flush();

        return new JsonResponse();
    }

}