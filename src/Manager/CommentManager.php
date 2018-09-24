<?php

namespace App\Manager;

use App\Entity\Comment;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Http\Discovery\Exception\NotFoundException;
use Nelmio\Alice\Throwable\Exception\Generator\Context\CachedValueNotFound;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UserManager
 * @package App\Manager
 */
class CommentManager
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
     * @param string $id
     * @param string $title
     * @param string $description
     * @param User $user
     */
    public function createComment(string $id,string $title,string $description, User $user)
    {
        $comment = new Comment($id, $title, $description, $user);

        $this->entityManager->persist($comment);

        $this->entityManager->flush();
    }

    /**
     * @param string $id
     * @param string $title
     * @param string $description
     */
    public function modifyComment(string $id,string $title,string $description)
    {
        /**@var Comment $comment*/

        $comment = $this->entityManager->getRepository(Comment::class)->findOneBy(['id'=>$id]);

        $comment->update($title, $description);

        $this->entityManager->persist($comment);

        $this->entityManager->flush();

    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function deleteComment($id)
    {
        $comment = $this->entityManager->getRepository(Comment::class)->find($id);

        if (empty($comment)) {
            throw new NotFoundHttpException("error while delete comment");
        }

        $this->entityManager->remove($comment);
        $this->entityManager->flush();

        return new JsonResponse();

    }


}

























































