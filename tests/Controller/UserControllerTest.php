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
    public function testLoadAllUserAction()
    {
        $mockFormFactoryInterface = $this->createMock(FormFactoryInterface::class);

        $mockUserManager = $this->createMock(UserManager::class);

        $mockUserManager
            ->expects($this->once())
            ->method('loadAllUser');

        $user = new UserController($mockUserManager, $mockFormFactoryInterface);

        $actual = $user->loadAllUserAction();

        $this->assertInstanceOf(JsonResponse::class, $actual);
        $this->assertEquals(200, $actual->getStatusCode());

    }

    public function testLoadAllUserActionError()
    {
        $mockUserManager = $this->createMock(UserManager::class);
        $mockFormFactoryInterface = $this->createMock(FormFactoryInterface::class);
        $mockNotFoundException = $this->createMock(NotFoundHttpException::class);

        $mockUserManager
            ->expects($this->once())
            ->method('loadAllUser')
            ->willThrowException($mockNotFoundException);

        $mockNotFoundException
            ->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(404);

        $userController = new UserController($mockUserManager, $mockFormFactoryInterface);

        $actual = $userController->loadAllUserAction();

        $this->assertInstanceOf(JsonResponse::class, $actual);
        $this->assertEquals(404, $actual->getStatusCode());
        $this->assertEquals('{"error_message":""}', $actual->getContent());
    }

    public function testLoadUserAction()
    {
        $mockFormFactoryInterface = $this->createMock(FormFactoryInterface::class);
        $mockUserManager = $this->createMock(UserManager::class);
        $mockUser = $this->createMock(User::class);
        $mockComment = $this->createMock(Comment::class);

        $mockUserManager
            ->expects($this->once())
            ->method('loadUser')
            ->with('025caf9e-e6e6-4aac-a45b')
            ->willReturn($mockUser, $mockComment);

        $obj = new UserController($mockUserManager, $mockFormFactoryInterface);

        $actual = $obj->loadUserAction("025caf9e-e6e6-4aac-a45b");

        $this->assertInstanceOf(JsonResponse::class, $actual);
        $this->assertEquals(200, $actual->getStatusCode());

    }

    public function testLoadUserErrorAction()
    {
        $mockFormFactoryInterface = $this->createMock(FormFactoryInterface::class);
        $mockUserManager = $this->createMock(UserManager::class);
        $mockNotFoundException = $this->createMock(NotFoundHttpException::class);

        $mockUserManager
            ->expects($this->once())
            ->method('loadUser')
            ->willThrowException($mockNotFoundException);

        $mockNotFoundException
            ->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(404);

        $objUserController = new UserController($mockUserManager, $mockFormFactoryInterface);

        $expected = $objUserController->loadUserAction('');

        $this->assertInstanceOf(JsonResponse::class, $expected);
        $this->assertEquals(404, $expected->getStatusCode());
    }

    public function testDeleteUserAction()
    {
        $mockUserManager = $this->createMock(UserManager::class);
        $mockFormFactoryInterface = $this->createMock(FormFactoryInterface::class);
        $objUserController = new UserController($mockUserManager, $mockFormFactoryInterface);

        $mockUserManager
            ->expects($this->once())
            ->method('deleteUser')
            ->with('025caf9e-e6e6-4aac-a45b');

        $actual = $objUserController->deleteUserAction("025caf9e-e6e6-4aac-a45b");

        $this->assertEquals(new JsonResponse(), $actual);
        $this->assertEquals(200, $actual->getStatusCode());
    }

    public function testDeleteUserOnErrorWhenUserDoesNotExist()
    {
        $mockUserManager = $this->createMock(UserManager::class);

        $mockFormFactoryInterface = $this->createMock(FormFactoryInterface::class);

        $mockNotFoundException = $this->createMock(NotFoundHttpException::class);

        $mockNotFoundException
            ->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(404);

        $mockUserManager
            ->expects($this->once())
            ->method('deleteUser')
            ->willThrowException($mockNotFoundException);


        $objUserController = new UserController($mockUserManager, $mockFormFactoryInterface);

        $actual = $objUserController->deleteUserAction("");

        $this->assertInstanceOf(JsonResponse::class, $actual);
        $this->assertEquals(404, $actual->getStatusCode());

    }

    public function testCreateUserAction()
    {
        $mockFormFactoryInterface = $this->createMock(FormFactoryInterface::class);
        $mockUserManager = $this->createMock(UserManager::class);
        $mockFormInterface = $this->createMock(FormInterface::class);
        $mockRequest = $this->createMock(Request::class);

        $mockFormFactoryInterface
            ->expects($this->once())
            ->method('create')
            ->willReturn($mockFormInterface);

        $mockFormInterface
            ->expects($this->once())
            ->method('submit')
            ->willReturn($mockRequest);

        $mockRequest
            ->expects($this->once())
            ->method('getContent')
            ->willReturn('{"lastname":"Doe","firstname":"Jonas","birthday":"2018-07-26"}');

        $mockFormInterface
            ->expects($this->once())
            ->method('getData')
            ->willReturn(['lastname' => 'Doe', 'firstname' => 'Jonas', 'birthday' => '2018-07-26']);

        $mockFormInterface
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $mockUserManager
            ->expects($this->once())
            ->method('createUser');

        $objUser = new UserController($mockUserManager, $mockFormFactoryInterface);

        $actual = $objUser->createUserAction($mockRequest);

        $content = json_decode($actual->getContent(), true);

        $this->assertArrayHasKey('id', $content);
        $this->assertEquals('Doe', $content['lastname']);
        $this->assertEquals('Jonas', $content['firstname']);
        $this->assertEquals('2018-07-26', $content['birthday']);

    }

    public function testCreateUserActionError()
    {
        $mockFormFactoryInterface = $this->createMock(FormFactoryInterface::class);
        $mockFormInterface = $this->createMock(FormInterface::class);
        $mockRequest = $this->createMock(Request::class);
        $mockUserManager =$this->createMock(UserManager::class);


        $mockFormFactoryInterface
            ->expects($this->once())
            ->method('create')
            ->willReturn($mockFormInterface);

        $mockFormInterface
            ->expects($this->once())
            ->method('submit')
            ->with(["id"=>"", "lastname"=>"", "firstname"=>"", "birthday"=>""])
            ->willReturnSelf();

        $mockRequest
            ->expects($this->once())
            ->method('getContent')
            ->willReturn('{"id":"","lastname":"","firstname":"","birthday":""}');

        $mockFormInterface
            ->expects($this->once())
            ->method('getData');

        $mockFormInterface
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(false);

        $userController = new UserController($mockUserManager,$mockFormFactoryInterface);

        $actual = $userController->createUserAction($mockRequest);

        $this->assertEquals(400, $actual->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $actual);
    }

    public function testModifyUserAction()
    {
        $mockFormFactoryInterface = $this->createMock(FormFactoryInterface::class);
        $mockFormInterface = $this->createMock(FormInterface::class);
        $mockUserManager = $this->createMock(UserManager::class);
        $mockRequest = $this->createMock(Request::class);
        $expected = '{"id":"4eb298dd-5cd7-4d10-9b9q","lastname":"tony","firstname":"montana","birthday":"1994-08-15"}';

        $mockFormFactoryInterface
            ->expects($this->once())
            ->method('create')
            ->willReturn($mockFormInterface);

        $mockFormInterface
            ->expects($this->once())
            ->method('submit')
            ->willReturn($mockRequest);

        $mockRequest
            ->expects($this->once())
            ->method("getContent")
            ->willReturn('{"id":"4eb298dd-5cd7-4d10-9b9q","lastname":"tony","firstname":"montana","birthday":"1994-08-15"}');

        $mockFormInterface
            ->expects($this->once())
            ->method('getData')
            ->willReturn(["id"=>"4eb298dd-5cd7-4d10-9b9q", "lastname" => "tony", "firstname" => "montana", "birthday" => "1994-08-15"]);

        $mockFormInterface
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $mockUserManager
            ->expects($this->once())
            ->method('modifyUser');

        $user = new UserController($mockUserManager, $mockFormFactoryInterface);

        $actual = $user->modifyUserAction($mockRequest, '4eb298dd-5cd7-4d10-9b9q');

        $this->assertInstanceOf(JsonResponse::class, $actual);
        $this->assertEquals($expected, $actual->getContent());
        $this->assertEquals(200, $actual->getStatusCode());
    }

    public function testModifyUserActionError()
    {
        $mockFormFactoryInterface = $this->createMock(FormFactoryInterface::class);
        $mockFormInterface = $this->createMock(FormInterface::class);
        $mockUserManager = $this->createMock(UserManager::class);
        $mockRequest = $this->createMock(Request::class);
        $mockFormErrorIterator = $this->createMock(FormErrorIterator::class);

        $mockFormFactoryInterface
            ->expects($this->once())
            ->method('create')
            ->willReturn($mockFormInterface);

        $mockFormInterface
            ->expects($this->once())
            ->method('submit')
            ->willReturn($mockRequest);

        $mockRequest
            ->expects($this->once())
            ->method("getContent")
            ->willReturn('{"id":"","lastname":"","firstname":"","birthday":""}');

        $mockFormInterface
            ->expects($this->once())
            ->method('getData')
            ->willReturn(["lastname" => "", "firstname" => "", "birthday" => ""]);

        $mockFormInterface
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(false);

        $mockFormInterface
            ->expects($this->once())
            ->method('getErrors')
            ->willReturn($mockFormErrorIterator);

        $objUser = new UserController($mockUserManager, $mockFormFactoryInterface);

        $actual = $objUser->modifyUserAction($mockRequest, "");

        $this->assertInstanceOf(JsonResponse::class, $actual);
        $this->assertEquals(400, $actual->getStatusCode());
    }
}

























