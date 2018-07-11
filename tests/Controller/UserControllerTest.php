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

        $responseJsonNull = new JsonResponse(null,404);

        $objUserController = new UserController($mockConnectBdd);

        $content = $objUserController->loadUserAction(55);
        $this->assertEquals($responseJsonNull , $content);




    }



}