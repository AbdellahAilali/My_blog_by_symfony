<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\User;
use App\Form\UserFormType;
use App\Manager\UserManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserControllerTest extends TestCase
{
    private $mockUserManager;
    private $mockFormFactoryInterface;
    private $mockFormInterface;
    private $mockNotFoundException;
    private $userController;
    private $mockRequest;

    /**
     * UserControllerTest constructor.
     * @param null|string $name
     * @param array       $data
     * @param string      $dataName
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->mockUserManager = $this->createMock(UserManager::class);
        $this->mockFormFactoryInterface = $this->createMock(FormFactoryInterface::class);
        $this->mockFormInterface = $this->createMock(FormInterface::class);
        $this->mockNotFoundException = $this->createMock(NotFoundHttpException::class);
        $this->userController = new UserController($this->mockUserManager, $this->mockFormFactoryInterface);
        $this->mockRequest = $this->createMock(Request::class);
    }

    public function testLoadAllUserAction()
    {
        $this->mockUserManager
            ->expects($this->once())
            ->method('loadAllUser');

        $actual = $this->userController->loadAllUserAction();

        $this->assertInstanceOf(JsonResponse::class, $actual);
        $this->assertEquals(200, $actual->getStatusCode());

    }

    public function testLoadAllUserActionError()
    {
        $this->mockUserManager
            ->expects($this->once())
            ->method('loadAllUser')
            ->willThrowException($this->mockNotFoundException);

        $this->mockNotFoundException
            ->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(404);

        $actual = $this->userController->loadAllUserAction();

        $this->assertInstanceOf(JsonResponse::class, $actual);
        $this->assertEquals(404, $actual->getStatusCode());
        $this->assertEquals('{"error_message":""}', $actual->getContent());
    }

    public function testLoadUserAction()
    {
        $mockUser = $this->createMock(User::class);
        $mockComment = $this->createMock(Comment::class);

        $this->mockUserManager
            ->expects($this->once())
            ->method('loadUser')
            ->with('025caf9e-e6e6-4aac-a45b')
            ->willReturn($mockUser, $mockComment);

        $actual = $this->userController->loadUserAction("025caf9e-e6e6-4aac-a45b");

        $this->assertInstanceOf(JsonResponse::class, $actual);
        $this->assertEquals(200, $actual->getStatusCode());

    }

    public function testLoadUserErrorAction()
    {
        $this->mockUserManager
            ->expects($this->once())
            ->method('loadUser')
            ->willThrowException($this->mockNotFoundException);

        $this->mockNotFoundException
            ->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(404);

        $expected = $this->userController->loadUserAction('');

        $this->assertInstanceOf(JsonResponse::class, $expected);
        $this->assertEquals(404, $expected->getStatusCode());
    }

    public function testDeleteUserAction()
    {
        $this->mockUserManager
            ->expects($this->once())
            ->method('deleteUser')
            ->with('025caf9e-e6e6-4aac-a45b');

        $actual = $this->userController->deleteUserAction("025caf9e-e6e6-4aac-a45b");

        $this->assertEquals(new JsonResponse(), $actual);
        $this->assertEquals(200, $actual->getStatusCode());
    }

    public function testDeleteUserOnErrorWhenUserDoesNotExist()
    {
        $this->mockNotFoundException
            ->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(404);

        $this->mockUserManager
            ->expects($this->once())
            ->method('deleteUser')
            ->willThrowException($this->mockNotFoundException);

        $actual = $this->userController->deleteUserAction("");

        $this->assertInstanceOf(JsonResponse::class, $actual);
        $this->assertEquals(404, $actual->getStatusCode());

    }

    public function testCreateUserAction()
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
            ->method('getContent')
            ->willReturn('{"lastname":"Doe","firstname":"Jonas","birthday":"2018-07-26"}');

        $this->mockFormInterface
            ->expects($this->once())
            ->method('getData')
            ->willReturn(['lastname' => 'Doe', 'firstname' => 'Jonas', 'birthday' => '2018-07-26']);

        $this->mockFormInterface
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $this->mockUserManager
            ->expects($this->once())
            ->method('createUser');

        $actual = $this->userController->createUserAction($this->mockRequest);

        $content = json_decode($actual->getContent(), true);

        $this->assertArrayHasKey('id', $content);
        $this->assertEquals('Doe', $content['lastname']);
        $this->assertEquals('Jonas', $content['firstname']);
        $this->assertEquals('2018-07-26', $content['birthday']);

    }

    public function testCreateUserActionError()
    {
        $mockFormErrorIterator = $this->createMock(FormErrorIterator::class);

        $this->mockFormFactoryInterface
            ->expects($this->once())
            ->method('create')
            ->willReturn($this->mockFormInterface);

        $this->mockFormInterface
            ->expects($this->once())
            ->method('submit')
            ->with(["id" => "", "lastname" => "", "firstname" => "", "birthday" => ""])
            ->willReturnSelf();

        $this->mockRequest
            ->expects($this->once())
            ->method('getContent')
            ->willReturn('{"id":"","lastname":"","firstname":"","birthday":""}');

        $this->mockFormInterface
            ->expects($this->once())
            ->method('getData');

        $this->mockFormInterface
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(false);

        $this->mockFormInterface
            ->expects($this->once())
            ->method('getErrors')
            ->willReturn($mockFormErrorIterator);

        $actual = $this->userController->createUserAction($this->mockRequest);

        $this->assertEquals(400, $actual->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $actual);
    }

    public function testModifyUserAction()
    {
        $expected = '{"id":"4eb298dd-5cd7-4d10-9b9q","lastname":"tony","firstname":"montana","birthday":"1994-08-15"}';

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
            ->willReturn('{"id":"4eb298dd-5cd7-4d10-9b9q","lastname":"tony","firstname":"montana","birthday":"1994-08-15"}');

        $this->mockFormInterface
            ->expects($this->once())
            ->method('getData')
            ->willReturn(["id" => "4eb298dd-5cd7-4d10-9b9q", "lastname" => "tony", "firstname" => "montana", "birthday" => "1994-08-15"]);

        $this->mockFormInterface
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $this->mockUserManager
            ->expects($this->once())
            ->method('modifyUser');

        $actual = $this->userController->modifyUserAction($this->mockRequest, '4eb298dd-5cd7-4d10-9b9q');

        $this->assertInstanceOf(JsonResponse::class, $actual);
        $this->assertEquals($expected, $actual->getContent());
        $this->assertEquals(200, $actual->getStatusCode());
    }

    public function testModifyUserActionError()
    {
        $mockFormErrorIterator = $this->createMock(FormErrorIterator::class);

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
            ->willReturn('{"id":"","lastname":"","firstname":"","birthday":""}');

        $this->mockFormInterface
            ->expects($this->once())
            ->method('getData')
            ->willReturn(["lastname" => "", "firstname" => "", "birthday" => ""]);

        $this->mockFormInterface
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(false);

        $this->mockFormInterface
            ->expects($this->once())
            ->method('getErrors')
            ->willReturn($mockFormErrorIterator);

        $actual = $this->userController->modifyUserAction($this->mockRequest, "");

        $this->assertInstanceOf(JsonResponse::class, $actual);
        $this->assertEquals(400, $actual->getStatusCode());
    }
}

























