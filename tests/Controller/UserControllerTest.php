<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Repository\RepositoryFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserControllerTest extends TestCase
{
    public function testLoadUserAction()
    {
        $responseExpected = '{"firstname":"toto","getLastname":"El","comments":[{"title":"titre","comment":"description"}]}';

        $mockConnectBdd = $this->createMock(EntityManager::class);
        $mockOBjRepo = $this->createMock(ObjectRepository::class);

        $mockConnectBdd
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($mockOBjRepo);

        $objComment = new Comment();
        $objComment->setTitle('titre');
        $objComment->setDescription("description");

        $objUser = new User();
        $objUser->setLastname("El");
        $objUser->setFirstname("toto");
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
            ->willReturn('{"lastname":"san","firstname":"gatama","dateNaissance":"2005-08-15T15:52:01+00:00"}');

        $mockEntity = $this->createMock(EntityManager::class);

        $mockEntity
            ->expects($this->once())
            ->method('persist');

        $mockEntity
            ->expects($this->once())
            ->method('flush');

        $objUser = new UserController($mockEntity);

        $content = $objUser->createUserAction($mockRequest);

        $this->assertEquals(new JsonResponse(), $content);
    }


    public function testModifyUserAction()
    {
        $mockEntity = $this->createMock(EntityManager::class);

        $mockRepo = $this->createMock(ObjectRepository::class);

        $mockEntity
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($mockRepo);

        $mockRepo
            ->expects($this->once())
            ->method('find')
            ->willReturn(new User());

        $mockRequest = $this->createMock(Request::class);

        $mockRequest
            ->expects($this->once())
            ->method("getContent")
            ->willReturn('{"id":"26ce92f5-0a6d-45e5-b0a3-b018f0101","lastname":"tony","firstname":"montana",
            "dateNaissance":"1994-08-15T15:52:01+00:00"}');

        $mockEntity
            ->expects($this->once())
            ->method('persist');

        $mockEntity
            ->expects($this->once())
            ->method('flush');


        $objUser = new UserController($mockEntity);

        $content = $objUser->modifyUserAction($mockRequest);

        $this->assertEquals(new JsonResponse(), $content);
    }

}
