<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\User;
use App\Manager\UserManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Repository\RepositoryFactory;
use PHPUnit\Framework\MockObject\Matcher\AnyInvokedCount;
use PHPUnit\Framework\TestCase;
use PHPUnit\Util\Json;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserControllerTest extends TestCase
{
    public function testLoadUserAction()
    {
        $mockFormFactoryInterface = $this->createMock(FormFactoryInterface::class);

        $mockUserManager = $this->createMock(UserManager::class);

        $mockUser = $this->createMock(User::class);

        $mockComment = $this->createMock(Comment::class);

        $mockUserManager
            ->expects($this->once())
            ->method('loadUser')
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

        /* $mockNotFoundException
             ->expects($this->once())
             ->method('getMessage')
             ->willReturn(['error_message'=>'']);*/

        $mockNotFoundException
            ->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(404);

        $objUserController = new UserController($mockUserManager, $mockFormFactoryInterface);

        $expected = $objUserController->loadUserAction('');

        $this->assertInstanceOf(JsonResponse::class, $expected);
        //$this->assertEquals(["error_message"=>""], $expected->getContent());
        $this->assertEquals(404, $expected->getStatusCode());
    }

    public function testDeleteUserAction()
    {
        $mockUserManager = $this->createMock(UserManager::class);

        $mockFormFactoryInterface = $this->createMock(FormFactoryInterface::class);


        $objUserController = new UserController($mockUserManager, $mockFormFactoryInterface);

        $actual = $objUserController->deleteUserAction("5b683d97a6f2f");

        $this->assertEquals(new JsonResponse(), $actual);
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
            ->willReturn('{"lastname":"Ailali","firstname":"Abdellah","birthday":"2018-07-26"}');

        $mockFormInterface
            ->expects($this->once())
            ->method('getData')
            ->willReturn(['lastname' => 'Ailali', 'firstname' => 'Abdellah', 'birthday' => '2018-07-26']);

        $mockFormInterface
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $mockUserManager
            ->expects($this->once())
            ->method('createUser');

        $objUser = new UserController($mockUserManager, $mockFormFactoryInterface);

        $expected = $objUser->createUserAction($mockRequest);

        $content = json_decode($expected->getContent(), true);

        $this->assertArrayHasKey('id', $content);

        $this->assertEquals('Ailali', $content['lastname']);
    }


    public function testModifyUserAction()
    {
        $mockFormFactoryInterface = $this->createMock(FormFactoryInterface::class);
        $mockFormInterface = $this->createMock(FormInterface::class);
        $mockUserManager = $this->createMock(UserManager::class);
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
            ->method("getContent")
            ->willReturn('{"id":"4eb298dd-5cd7-4d10-9b9q","lastname":"tony","firstname":"montana","birthday":"1994-08-15"}');

        $mockFormInterface
            ->expects($this->once())
            ->method('getData')
            ->willReturn(["lastname" => "tony", "firstname" => "montana", "birthday" => "1994-08-15"]);

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
        $this->assertEquals('{"id":"4eb298dd-5cd7-4d10-9b9q","lastname":"tony","firstname":"montana","birthday":"1994-08-15"}', $actual->getContent());
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

}

























