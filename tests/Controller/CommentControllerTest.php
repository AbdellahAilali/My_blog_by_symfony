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


        $mockRequest
            ->expects($this->once())
            ->method("getContent")
            ->willReturn('{"id":"sldk52fsxsxzc2sfe5f","title":"Paris","description":"Paris de jour comme de nuit","user_id":"32132dsf132ds1f3ds21fsd"}');

        $mockEntity
            ->expects($this->once())
            ->method("getRepository")
            ->willReturn($mockObjectRepo);

        $mockObjectRepo
            ->expects($this->once())
            ->method("find")
            ->willReturn(new User());

        $mockEntity
            ->expects($this->once())
            ->method("persist");

        $mockEntity
            ->expects($this->once())
            ->method("flush");

        $objComment = new CommentController($mockEntity);

        $comment = $objComment->createCommentAction($mockRequest);

        $response = ["id"=>"sldk52fsxsxzc2sfe5f","title"=>"Paris","description"=>"Paris de jour comme de nuit","user_id"=>"32132dsf132ds1f3ds21fsd"];

        $this->assertEquals(new JsonResponse($response), $comment);

    }

    public function testModifyCommentAction()
    {
        $mockEntity = $this->createMock(EntityManager::class);
        $mockRepo = $this->createMock(ObjectRepository::class);

        $mockEntity
            ->expects($this->once())
            ->method("getRepository")
            ->willReturn($mockRepo);
        $mockObjectComment = $this->createMock(Comment::class);

        $mockRepo
            ->expects($this->once())
            ->method("find")
            ->willReturn($mockObjectComment);

        $mockRequest = $this->createMock(Request::class);

        $mockRequest
            ->expects($this->once())
            ->method("getContent")
            ->willReturn('{"id":"commentaire1","title":"mon new title","description":"ma new description","user_id":"cd"}');

        $mockObjectComment
            ->expects($this->once())
            ->method("setTitle")
            ->willReturn($mockObjectComment);

        $mockObjectComment
            ->expects($this->once())
            ->method("setDescription")
            ->willReturn($mockObjectComment);

        $mockEntity
            ->expects($this->once())
            ->method("persist");

        $mockEntity
            ->expects($this->once())
            ->method("flush");

        $comment = new CommentController($mockEntity);

        $content = $comment->modifyCommentAction($mockRequest, "commentaire1");

        $this->assertEquals(new JsonResponse(), $content);

    }


    public function testDeleteCommentAction()
    {
        $mockEntity = $this->createMock(EntityManager::class);
        $mockRepo = $this->createMock(ObjectRepository::class);

        $mockRepo
            ->expects($this->once())
            ->method("find")
            ->willReturn(new Comment());

        $mockEntity
            ->expects($this->once())
            ->method("getRepository")
            ->willReturn($mockRepo);

        $mockEntity
            ->expects($this->once())
            ->method("remove");

        $mockEntity
            ->expects($this->once())
            ->method("flush");

        $comment = new CommentController($mockEntity);

        $content = $comment->deleteCommentAction("commentaire1");

        $this->assertEquals(new JsonResponse(), $content);

    }

}