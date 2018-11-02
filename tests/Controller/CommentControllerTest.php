<?php

namespace Test\Controller;

use App\Controller\CommentController;
use App\Entity\User;
use App\Manager\CommentManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CommentControllerTest extends TestCase
{
    private $mockRequest;
    private $mockFormFactoryInterface;
    private $mockFormInterface;
    private $mockCommentManager;
    private $commentController;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->mockRequest = $this->createMock(Request::class);
        $this->mockFormFactoryInterface = $this->createMock(FormFactoryInterface::class);
        $this->mockFormInterface = $this->createMock(FormInterface::class);
        $this->mockCommentManager = $this->createMock(CommentManager::class);
        $this->commentController = new CommentController($this->mockCommentManager, $this->mockFormFactoryInterface);
    }

    public function testCreateCommentAction()
    {
        $requestGetData = ["title" => "My Title PNL", "description" => "My description", "user" => "36fb3b5c-da75-4e4c-8697-8b83460b1a55"];

        $this->mockFormFactoryInterface
            ->expects($this->once())
            ->method('create')
            ->willReturn($this->mockFormInterface);

        $this->mockFormInterface
            ->expects($this->once())
            ->method('submit')
            ->willReturn($this->mockRequest);

        $this->mockRequest
            ->expects($this->once())
            ->method('getContent')
            ->willReturn($this->mockRequest);

        $data = $requestGetData;
        $data['user'] = new User("36fb3b5c-da75-4e4c-8697-8b83460b1a55", "Johnn", 'Doe', new \DateTime());

        $this->mockFormInterface
            ->expects($this->once())
            ->method('getData')
            ->willReturn($data);

        $this->mockFormInterface
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $commentController = new CommentController($this->mockCommentManager, $this->mockFormFactoryInterface);

        $actual = $commentController->createCommentAction($this->mockRequest);

        $actualGetContent = json_decode($actual->getContent(), true);

        $this->assertInstanceOf(JsonResponse::class, $actual);
        $this->assertEquals(200, $actual->getStatusCode());
        $this->assertEquals('My Title PNL', $actualGetContent["title"]);
        $this->assertEquals('My description', $actualGetContent["description"]);
        $this->assertEquals('36fb3b5c-da75-4e4c-8697-8b83460b1a55', $actualGetContent["user"]);
    }

    public function testCreateCommentActionError()
    {
        $mockFormErrorIteratoe = $this->createMock(FormErrorIterator::class);

        $this->mockFormFactoryInterface
            ->expects($this->once())
            ->method('create')
            ->willReturn($this->mockFormInterface);

        $this->mockFormInterface
            ->expects($this->once())
            ->method('submit')
            ->willReturn($this->mockRequest);

        $this->mockRequest
            ->expects($this->once())
            ->method('getContent')
            ->willReturn('{}');

        $this->mockFormInterface
            ->expects($this->once())
            ->method('getData')
            ->willReturn([]);

        $this->mockFormInterface
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(false);

        $this->mockFormInterface
            ->expects($this->once())
            ->method('getErrors')
            ->willReturn($mockFormErrorIteratoe);

        $actual = $this->commentController->createCommentAction($this->mockRequest);

        $this->assertInstanceOf(JsonResponse::class, $actual);
        $this->assertEquals(400, $actual->getStatusCode());

    }

    public function testModifyCommentAction()
    {
        $this->mockFormFactoryInterface
            ->expects($this->once())
            ->method('create')
            ->willReturn($this->mockFormInterface);

        $this->mockFormInterface
            ->expects($this->once())
            ->method('submit')
            ->willReturn($this->mockRequest);

        $this->mockRequest
            ->expects($this->once())
            ->method("getContent")
            ->willReturn("{'title':'My complex Title','description':'My complex description'}");

        $this->mockFormInterface
            ->expects($this->once())
            ->method("getData")
            ->willReturn(['title' => 'My complex Title', 'description' => 'My complex description']);

        $this->mockFormInterface
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $actual = $this->commentController->modifyCommentAction($this->mockRequest, 'c6d90297-d196-4d31-ab93');

        $expected = '{"id":"c6d90297-d196-4d31-ab93","title":"My complex Title","description":"My complex description"}';

        $this->assertEquals($expected, $actual->getContent());
        $this->assertEquals(200, $actual->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $actual);
    }

    public function testModifyCommentActionError()
    {
        $mockFormErrorIterator = $this->createMock(FormErrorIterator::class);

        $this->mockFormFactoryInterface
            ->expects($this->once())
            ->method('create')
            ->willReturn($this->mockFormInterface);

        $this->mockFormInterface
            ->expects($this->once())
            ->method('submit')
            ->willReturnSelf();

        $this->mockRequest
            ->expects($this->once())
            ->method('getContent')
            ->willReturn('{}');

        $this->mockFormInterface
            ->expects($this->once())
            ->method('getData')
            ->willReturn([]);

        $this->mockFormInterface
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(false);

        $this->mockFormInterface
            ->expects($this->once())
            ->method('getErrors')
            ->willReturn($mockFormErrorIterator);

        $actual = $this->commentController->modifyCommentAction($this->mockRequest, '');

        $this->assertInstanceOf(JsonResponse::class, $actual);
        $this->assertEquals(400, $actual->getStatusCode());
    }

    public function testDeleteCommentAction()
    {
        $this->mockCommentManager
            ->expects($this->once())
            ->method('deleteComment');

        $actual = $this->commentController->deleteCommentAction('c6d90297-d196-4d31-ab93');

        $this->assertEquals(200, $actual->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $actual);
    }

    public function testDeleteCommentActionError()
    {
        $mockNotFoundException = $this->createMock(NotFoundHttpException::class);

        $this->mockCommentManager
            ->expects($this->once())
            ->method('deleteComment')
            ->willThrowException($mockNotFoundException);

        $mockNotFoundException
            ->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(400);

        $actual = $this->commentController->deleteCommentAction('');

        $this->assertInstanceOf(JsonResponse::class, $actual);
        $this->assertEquals(400, $actual->getStatusCode());





    }
}