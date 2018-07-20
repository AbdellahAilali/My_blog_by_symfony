<?php
/**
 * Created by PhpStorm.
 * User: abdellah
 * Date: 20/07/18
 * Time: 10:49
 */

namespace Test\Controller;


use App\Controller\CommentController;
use App\Entity\Comment;
use PHPUnit\Framework\TestCase;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class CommentControllerTest extends TestCase
{
    public function testCreateCommentAction()
    {
        $mockEntity = $this->createMock(EntityManager::class);
        $mockObjectRepo = $this->createMock(ObjectRepository::class);
        $mockRequest = $this->createMock(Request::class);
        $mockUser = $this->createMock(User::class);

        $mockRequest
            ->expects($this->once())
            ->method("getContent")
            ->willReturn('{"id":"commentaire1","title":"mon new title","description":"ma new description","user_id":"cd"}');

        $mockEntity
            ->expects($this->once())
            ->method("getRepository")
            ->willReturn($mockObjectRepo);

        $mockObjectRepo
            ->expects($this->once())
            ->method("find")
            ->willReturn($mockUser);

        $mockObjectComment = $this->createMock(Comment::class);

       /* $mockObjectComment
            ->expects($this->once())
            ->method('setId')
            ->willReturn($mockObjectComment);

        $mockObjectComment
            ->expects($this->once())
            ->method('setTitle')
            ->willReturn($mockObjectComment);

        $mockObjectComment
            ->expects($this->once())
            ->method('setDescription')
            ->willReturn($mockObjectComment);

        $mockObjectComment
            ->expects($this->once())
            ->method('setUser')
            ->willReturn($mockUser);*/

        $mockEntity
            ->expects($this->once())
            ->method("persist");

        $mockEntity
            ->expects($this->once())
            ->method("flush");

        $objComment = new CommentController($mockEntity);

        $comment = $objComment->createCommentAction($mockRequest);

        $this->assertEquals(new JsonResponse(), $comment);

    }

}