<?php
/**
 * Created by PhpStorm.
 * User: abdellah
 * Date: 19/07/18
 * Time: 16:59
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Util\Json;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class CommentController
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
     * @Route("/comment", name="add_comment", methods={"POST"})
     * @return JsonResponse
     */
    public function createCommentAction(Request $request)
    {
        //je crée un tableau d'erreur ou je insert toute mais erreur envoyer par le client.
        $errors = [];

        $resultJson = $request->getContent();
        if (empty($request))
        {
            return new JsonResponse(null,404);
        }
        $result = json_decode($resultJson);
        if (!isset($result->id)){
           $errors[] = 'Field "id" is missing in the request';
        }
        if (!isset($result->title)){
            $errors[] = 'Field "title" is missing in the request';
        }
        if (!isset($result->description)){
            $errors[] = 'Field "description" is missing in the request';
        }
        if (!isset($result->user_id)){
            $errors[] = 'Field "user_id" is missing in the request';
        }
        if (!empty($errors)){
            return new JsonResponse($errors,400);
        }


        $id = $result->id;
        $title = $result->title;
        $description = $result->description;
        $userId = $result->user_id;

        $user = $this->entityManager
            ->getRepository(User::class)
            ->find($userId);

        $comment = new Comment();

        $comment->setId($id);
        $comment->setTitle($title);
        $comment->setDescription($description);
        $comment->setUser($user);

        $em = $this->entityManager;

        $em->persist($comment);
        $em->flush();

        //renvoie id pour pouvoir effectuer mes tests
        return new JsonResponse(["id"=>$id, "title"=> $title, "description"=>$description,"user_id"=>$userId]);


    }
    /**
     * @todo  modifier nom des routes, rajouter une sortie erreur
     */
    /**
     * @route("/modify_comment/{id}", name="modify_comment", methods={"PUT"})
     * @param $id
     * @return JsonResponse
     */

    public function modifyCommentAction(Request $request, $id)
    {
        $comment = $this->entityManager
            ->getRepository(Comment::class)
            ->find($id);

        if (empty($comment))
        {
            return new JsonResponse("Aucun commentaire trouver",404);
        }

        $resultJson = $request->getContent();
        $result = json_decode($resultJson);

        $title = $result->title;
        $description = $result->description;


        $comment->setTitle($title);
        $comment->setDescription($description);

        $em = $this->entityManager;

        $em->persist($comment);
        $em->flush();

        return new JsonResponse();
    }


    /**
     * @route("/delete_comment/{id}", name="delete_comment", methods={"DELETE"})
     * @return JsonResponse
     * @param $id
     */

    public function deleteCommentAction($id)
    {
        $comment = $this->entityManager
            ->getRepository(Comment::class)
            ->find($id);

        if (empty($comment))
        {
            return new JsonResponse(null, 404);
        }

        $this->entityManager->remove($comment);
        $this->entityManager->flush();

        return new JsonResponse();

    }
}