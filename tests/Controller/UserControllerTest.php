<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Repository\RepositoryFactory;
use PHPUnit\Framework\MockObject\Matcher\AnyInvokedCount;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserControllerTest extends TestCase
{
   public function testLoadUserAction()
    {
        $responseExpected = '{"firstname":"rick","lastname":"grimm","comments":[{"title":"walking dead","comment":"la marche des zombie, super cool, je vous la conseil"}]}';

        $mockConnectBdd = $this->createMock(EntityManager::class);
        $mockOBjRepo = $this->createMock(ObjectRepository::class);

        $mockConnectBdd
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($mockOBjRepo);

        $objComment = new Comment();
        $objComment->setTitle('walking dead');
        $objComment->setDescription("la marche des zombie, super cool, je vous la conseil");

        $objUser = new User();
        $objUser->setLastname("grimm");
        $objUser->setFirstname("rick");
        $objUser->addComment($objComment);

        $mockOBjRepo
            ->expects($this->once())
            ->method('find')
            ->willReturn($objUser);

        $obj = new UserController($mockConnectBdd);

        $content = $obj->loadUserAction("cd72f69f-ae27-4257-bd0c-1aeff64b6f60")->getContent();

        $this->assertEquals($responseExpected, $content);

    }

    public function testLoadUserActionError()
    {
        $mockConnectBdd = $this->createMock(EntityManager::class);
        $mockOBjRepo = $this->createMock(ObjectRepository::class);

        $mockConnectBdd
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($mockOBjRepo);

        $mockOBjRepo
            ->expects($this->once())
            ->method('find')
            ->willReturn(null);

        $responseJsonNull = new JsonResponse(null, 404);

        $objUserController = new UserController($mockConnectBdd);

        $content = $objUserController->loadUserAction(55);
        $this->assertEquals($responseJsonNull, $content);
    }

    public function testDeleteUserAction()
    {
        $response = (new JsonResponse("ok", 200));

        $mockConnectBdd = $this->createMock(EntityManager::class);
        $mockOBjRepo = $this->createMock(ObjectRepository::class);

        $mockConnectBdd
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($mockOBjRepo);

        $mockUser = $this->createMock(User::class);

        $mockOBjRepo
            ->expects($this->once())
            ->method('find')
            ->willReturn($mockUser);

        $objUserController = new UserController($mockConnectBdd);

        $content = $objUserController->deleteUserAction("cd72f69f-ae27-4257-bd0c-1aeff64b6f60");

        $this->assertEquals($response, $content);
    }

    public function testDeleteUserError()
    {
        $response = new JsonResponse("no", 404);

        $mockConnectBdd = $this->createMock(EntityManager::class);
        $mockOBjRepo = $this->createMock(ObjectRepository::class);

        $mockConnectBdd
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($mockOBjRepo);

        //$mockUser = $this->createMock(User::class);

        $mockOBjRepo
            ->expects($this->once())
            ->method('find')
            ->willReturn(null);

        $objUserController = new UserController($mockConnectBdd);

        $content = $objUserController->deleteUserAction("cd72f69f-ae27-4257-bd0c-1aeff64b6f60");

        $this->assertEquals($response, $content);

    }

    public function testCreateUserAction()
    {
        $mockRequest = $this->createMock(Request::class);

        $mockRequest
            ->expects($this->once())
            ->method('getContent')
            ->willReturn('{"lastname":"Ailali","firstname":"Abdellah","birthday":"2018-07-26"}');

        $mockEntity = $this->createMock(EntityManager::class);

        $mockEntity
            ->expects($this->once())
            ->method('persist');

        $mockEntity
            ->expects($this->once())
            ->method('flush');

        $objUser = new UserController($mockEntity);

        /** @var JsonResponse $jsonResponse */
        $jsonResponse = $objUser->createUserAction($mockRequest);

        $content = json_decode($jsonResponse->getContent(), true);

        $this->assertArrayHasKey('id', $content);
        $this->assertEquals('Ailali', $content['lastname']);
    }


    public function testModifyUserAction()
    {
        $mockEntity = $this->createMock(EntityManager::class);

        $mockRepo = $this->createMock(ObjectRepository::class);

        $mockEntity
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($mockRepo);

        $mockUser = $this->createMock(User::class);

        $mockRepo
            ->expects($this->once())
            ->method('find')
            ->willReturn($mockUser);

        $mockRequest = $this->createMock(Request::class);

        $mockRequest
            ->expects($this->once())
            ->method("getContent")
            ->willReturn('{"id":"cd72f69f-ae27-4257-bd0c-1aeff64b6f60","lastname":"tony","firstname":"montana",
            "birthday":"1994-08-15T15:52:01+00:00"}');

        $mockUser
            ->expects($this->once())
            ->method("setLastname")
            ->willReturn($mockUser);

        $mockUser
            ->expects($this->once())
            ->method("setFirstname")
            ->willReturn($mockUser);

        $mockUser
            ->expects($this->once())
            ->method("setBirthday")
            ->willReturn($mockUser);


        $mockEntity
            ->expects($this->once())
            ->method('persist');

        $mockEntity
            ->expects($this->once())
            ->method('flush');


        $objUser = new UserController($mockEntity);

        $content = $objUser->modifyUserAction($mockRequest, "cd72f69f-ae27-4257-bd0c-1aeff64b6f60");

        $this->assertEquals(new JsonResponse(), $content);
    }

    public function testModifyUserActionError()
    {
        $responserror = new JsonResponse("aucun user trouver", 404);
        $mockEntity = $this->createMock(EntityManager::class);

        $mockRepo = $this->createMock(ObjectRepository::class);

        $mockEntity
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($mockRepo);

        $mockRepo
            ->expects($this->once())
            ->method('find')
            ->willReturn(null);

        $mockRequest = $this->createMock(Request::class);

        $objUser = new UserController($mockEntity);

        $content = $objUser->modifyUserAction($mockRequest, "");

        $this->assertEquals($responserror, $content);
    }

    public function testLoadAllUserAction()
    {
        $mockEntity = $this->createMock(EntityManager::class);
        $mockRepo = $this->createMock(ObjectRepository::class);

        $mockEntity
            ->expects($this->once())
            ->method("getRepository")
            ->willReturn($mockRepo);

        $user = new User();
        $user->setId("001");
        $user->setFirstname("hulk");
        $user->setLastname("hogan");
        $user->setBirthday(new \DateTime("1993-05-01"));

        $user2 = new User();
        $user2->setId("002");
        $user2->setFirstname("mike");
        $user2->setLastname("Tyson");
        $user2->setBirthday(new \DateTime("1983-05-01"));


        $mockRepo
            ->expects($this->once())
            ->method("findAll")
            ->willReturn([$user, $user2]);

        $user = new UserController($mockEntity);

        $response = $user->loadAllUserAction();

        $expected = '[{"id":"001","firstname":"hulk","lastname":"hogan","birthday":"1993-05-01"},{"id":"002","firstname":"mike","lastname":"Tyson","birthday":"1983-05-01"}]';

        $this->assertEquals($expected, $response->getContent());

    }

}

























