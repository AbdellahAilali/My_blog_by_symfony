<?php

namespace Test\Manager;

use App\Entity\User;
use App\Manager\UserFileManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class UserFileManagerTest extends TestCase
{

    public function testCreate()
    {
        $mockEntityManagerInterface = $this->createMock(EntityManagerInterface::class);
        $mockObjectRepository = $this->createMock(ObjectRepository::class);
        $path = 'listUser.csv';

        $users = new User("ds258sx-d2gsd5-25bg8-9nt1q3", 'Doe','James', new \DateTime('01/01/2000'));

        $mockEntityManagerInterface
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($mockObjectRepository);


        $mockObjectRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$users]);

        $actual = new UserFileManager($mockEntityManagerInterface);

        $actual->create($path);

    }

}