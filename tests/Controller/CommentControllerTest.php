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
use App\Manager\CommentManager;
use PHPUnit\Framework\TestCase;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\Bundle\TestBundle\TestServiceContainer\UnusedPrivateService;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class CommentControllerTest extends TestCase
{
    public function testCreateCommentAction()
    {
        $requestData = [
            'title' => 'My simple comment',
            'description' => 'My simple description',
            'user' => '36fb3b5c-da75-4e4c-8697-8b83460b1a55'
        ];

        $mockRequest = $this->createMock(Request::class);
        $mockFormFactory =$this->createMock(FormFactoryInterface::class);
        $mockForm =$this->createMock(FormInterface::class);
        $mockCommentManager = $this->createMock(CommentManager::class);

        $mockRequest
            ->expects($this->once())
            ->method("getContent")
            ->willReturn(json_encode($requestData));

        $formData = $requestData;
        $formData['user'] = new User('b0e047b9-d1a6-4610-bbad-e2fb77f3b7de', 'Ailalai', 'Abdellah', new \DateTime());
        $mockForm
            ->expects($this->once())
            ->method('getData')->willReturn($formData);

        $mockForm
            ->expects($this->once())
            ->method('isValid')->willReturn(true);

        $mockFormFactory
            ->expects($this->once())
            ->method('create')->willReturn($mockForm);

        $mockCommentManager
            ->expects($this->once())
            ->method('createComment')->with(
                $this->isType('string'),
                $formData['title'],
                $formData['description'],
                $formData['user']
            );

        $objComment = new CommentController($mockCommentManager, $mockFormFactory);
        $response = $objComment->createCommentAction($mockRequest);

        $content = json_decode($response->getContent(), true);


        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('title', $content);
        $this->assertArrayHasKey('description', $content);
        $this->assertArrayHasKey('user', $content);

        $this->assertEquals('My simple comment', $content['title']);
        $this->assertEquals('My simple description', $content['description']);
        $this->assertEquals('b0e047b9-d1a6-4610-bbad-e2fb77f3b7de', $content['user']);
    }

    public function testModifyCommentAction()
    {
        $requestData = [
            'id'=> 'b0e047b9-d1a6-4610-bbad-e2fb77f3b7de',
            'title' => 'My simple comment',
            'description' => 'My simple description',
            'user' => '36fb3b5c-da75-4e4c-8697-8b83460b1a55'
        ];

        $mockFormFactory =$this->createMock(FormFactoryInterface::class);
        $mockForm =$this->createMock(FormInterface::class);
        $mockCommentManager = $this->createMock(CommentManager::class);
        $mockRequest = $this->createMock(Request::class);


        $mockRequest
            ->expects($this->once())
            ->method("getContent")
            ->willReturn(json_encode($requestData));


        $mockForm
            ->expects($this->once())
            ->method('submit')
            ->willReturn('b0e047b9-d1a6-4610-bbad-e2fb77f3b7de', 'title2', 'desc2','36fb3b5c-da75-4e4c-8697-8b83460b1a55');

        $mockForm
            ->expects($this->once())
            ->method("getData")
            ->willReturn($requestData);

        $comment = new CommentController($mockCommentManager,$mockFormFactory);

        $content = $comment->modifyCommentAction($mockRequest,'b0e047b9-d1a6-4610-bbad-e2fb77f3b7de');

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