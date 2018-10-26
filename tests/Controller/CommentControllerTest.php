<?php

namespace Test\Controller;

use App\Controller\CommentController;
use App\Entity\User;
use App\Manager\CommentManager;
use PHPUnit\Framework\TestCase;
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
        $mockFormFactory = $this->createMock(FormFactoryInterface::class);
        $mockForm = $this->createMock(FormInterface::class);
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
        $mockFormFactoryInterface = $this->createMock(FormFactoryInterface::class);
        $mockForm = $this->createMock(FormInterface::class);
        $mockCommentManager = $this->createMock(CommentManager::class);
        $mockRequest = $this->createMock(Request::class);

        $mockFormFactoryInterface
            ->expects($this->once())
            ->method('create')
            ->willReturn($mockForm);

        $mockForm
            ->expects($this->once())
            ->method('submit')
            ->willReturn($mockRequest);

        $mockRequest
            ->expects($this->once())
            ->method("getContent")
            ->willReturn("{'title':'My complex comment','description':'My complex description'}");

        $mockForm
            ->expects($this->once())
            ->method("getData")
            ->willReturn(['title' => 'My complex comment', 'description' => 'My complex description']);

        $mockForm
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $comment = new CommentController($mockCommentManager, $mockFormFactoryInterface);

        $actual = $comment->modifyCommentAction($mockRequest, '5bd07aed1357d');

        $expected = '{"id":"5bd07aed1357d","title":"My complex comment","description":"My complex description"}';

        $this->assertEquals($expected, $actual->getContent());
        $this->assertEquals(200, $actual->getStatusCode());
    }

    public function testDeleteCommentAction()
    {
        $mockCommentManager = $this->createMock(CommentManager::class);
        $mockFormFactoryInterface = $this->createMock(FormFactoryInterface::class);

        $mockCommentManager
            ->expects($this->once())
            ->method('deleteComment');

        $comment = new CommentController($mockCommentManager, $mockFormFactoryInterface);

        $actual = $comment->deleteCommentAction('5bd07aed1357d');

        $this->assertEquals(200, $actual->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $actual);


    }

}