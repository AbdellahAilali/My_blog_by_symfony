<?php
namespace App\Controller;


use App\Entity\Comment;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Util\Json;
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

        $user = $this->entityManager
            ->getRepository(User::class)
            ->find($id);

        if (empty($user)) {
            return new JsonResponse(null, 404);
        }

        $result = [];
        $result["firstname"] = $user->getFirstname();
        $result["lastname"] = $user->getLastname();

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
     * @Route ("/user_delete/{id}", name="user_delete", methods={"DELETE"})
     * @param $id
     * @return JsonResponse
     */
    public function deleteUserAction($id)
    {
        /** @var User $user */

        $user = $this->entityManager
            ->getRepository(User::class)
            ->find($id);

        if (empty($user)) {
            return new JsonResponse("no", 404);
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return new JsonResponse("ok", 200);
    }


    /**
     * @Route ("/user", name="create_user", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function createUserAction(Request $request)
    {
        //je crée un tableau d'erreur ou je insert toute mais erreur envoyer par le client.
        $errors = [];

        /** @var Request $request */

        $resultJson = $request->getContent();
        if (empty($request)) {
            return new JsonResponse(null, 404);
        }
        $result = json_decode($resultJson);

        //utilisationde la class stdClass pour avoir
        // acces au valeurs  decode

        if (!isset($result->lastname)) {
            $errors[] = 'Field "lastname" is missing in the request';
        }
        if (!isset($result->firstname)) {
            $errors[] = 'Field "firstname" is missing in the request';
        }
        if (!isset($result->birthday)) {
            $errors[] = 'Field "DateNaissance" is missing in the request';
        }
        if (!empty($errors)) {
            return new JsonResponse($errors, 400);
        }
        //uniqid sert a crée un id automatiquement
        $id = uniqid();
        $lastname = $result->lastname;
        $firstname = $result->firstname;
        $date = $result->birthday;

        $newUser = new User();
        $newUser->setId($id);
        $newUser->setLastname($lastname);
        $newUser->setFirstname($firstname);
        $dateTime = new \DateTime($date);
        $newUser->setBirthday($dateTime);

        $birthday = $dateTime->format('Y-m-d');

        $em = $this->entityManager;

        $em->persist($newUser);

        $em->flush();
        //renvoie l'id en json
        return new JsonResponse(['id' => $id, "lastname"=>$lastname, "firstname"=>$firstname, "birthday"=>$birthday]);

    }

    /**
     * @Route("/user/modify/{id}", name="modify_user",methods={"PUT"})
     * @param Request $request
     * @return JsonResponse
     */

    public function modifyUserAction(Request $request, $id)
    {
        /** @var User $user */
        /** @var Request $request */
        $user = $this->entityManager
            ->getRepository(User::class)
            ->find($id);

        if (empty($user)) {
            return new JsonResponse("aucun user trouver", 404);
        }

        $resultJson = $request->getContent();

        $result = json_decode($resultJson);

        $lastname = $result->lastname;
        $firstname = $result->firstname;
        $date = $result->birthday;

        $user->setLastname($lastname);
        $user->setFirstname($firstname);
        $user->setBirthday(new \DateTime($date));

        $em = $this->entityManager;

        $em->persist($user);

        $em->flush();

        return new JsonResponse();

    }

    /**
     * @return JsonResponse
     * @Route ("/userAll", name="user_all", methods={"GET"})
     */
    public function loadAllUserAction()
    {
        /** @var User[] $users */
        $users = $this->entityManager
            ->getRepository(User::class)
            ->findAll();

        $tabUser = [];

        foreach ($users as $key => $user) {

            $tabUser[$key] = [
                "id" => $user->getId(),
                "firstname" => $user->getFirstname(),
                "lastname" => $user->getLastname(),
                //j'utilise l& function fomat pour lui dire que je ne souhaite avoir
                //y-m-d, car il me renvoie un objet avec tout les caractéristique.
                "birthday"=>$user->getBirthday()->format('Y-m-d'),
            ];
            //var_dump($user->getBirthday()->format('Y-m-d'));

            foreach ($user->getComments() as $comment) {
                $tabUser[$key]['comments'][] = [
                    "title" => $comment->getTitle(),
                    "comment" => $comment->getDescription()
                ];
            }
        }
        return new JsonResponse($tabUser);
    }




}