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
        $resultJson = $request->getContent();

        $result = json_decode($resultJson);

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

        return new JsonResponse();
    }
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
        $comment = $this->entityManager->getRepository(Comment::class)->find($id);

        if (empty($comment))
        {
            return new JsonResponse(null, 404);
        }

        $this->entityManager->remove($comment);
        $this->entityManager->flush();

        return new JsonResponse();

    }
}