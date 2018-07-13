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
            ->method('findOneBy')
            ->willReturn($objUser);

        $obj = new UserController($mockConnectBdd);

        $content = $obj->loadUserAction(1)->getContent();

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
            ->method('findOneBy')
            ->willReturn(null);

        $responseJsonNull = new JsonResponse(null, 404);

        $objUserController = new UserController($mockConnectBdd);

        $content = $objUserController->loadUserAction(55);
        $this->assertEquals($responseJsonNull, $content);
    }

    public function testDeleteUser()
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
            ->method('findOneBy')
            ->willReturn($mockUser);

        $objUserController = new UserController($mockConnectBdd);

        $content = $objUserController->deleteUser(1);

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
            ->method('findOneBy')
            ->willReturn(null);

        $objUserController = new UserController($mockConnectBdd);

        $content = $objUserController->deleteUser(1);

        $this->assertEquals($response, $content);

    }

    public function testCreateUserAction()
    {
        $mockRequest = $this->createMock(Request::class);

        $mockRequest
            ->expects($this->once())
            ->method('getContent')
            ->willReturn('{"lastname":"san","firstname":"gatama","dateNaissance":"2005-08-15T15:52:01+00:00"}') ;

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


}






















