<?php

namespace Test\Manager;


use App\Entity\Comment;
use App\Entity\User;
use App\Manager\UserManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManagerInterface;

class UserManagerTest extends TestCase
{
    private $mockEntityManager;
    private $mockObjectRepository;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->mockEntityManager = $this->createMock(EntityManager::class);
        $this->mockObjectRepository = $this->createMock(ObjectRepository::class);
    }


    public function testCreateUser()
    {
        $this->mockEntityManager
            ->expects($this->once())
            ->method('persist');

        $this->mockEntityManager
            ->expects($this->once())
            ->method('flush');

        $userManager = new UserManager($this->mockEntityManager);

        $userManager->createUser('1c410025-096e-43c8-97c5-501bc982a836', 'Sool', 'King', new \DateTime('01/02/1993'));
    }

    public function testModifyUser()
    {
        $mockUser = $this->createMock(User::class);

        $this->mockEntityManager
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->mockObjectRepository);

        $this->mockObjectRepository
            ->expects($this->once())
            ->method('findOneby')
            ->willReturn($mockUser);

        $mockUser
            ->expects($this->once())
            ->method('update');

        $this->mockEntityManager
            ->expects($this->once())
            ->method('persist');


        $this->mockEntityManager
            ->expects($this->once())
            ->method('flush');

        $user = new UserManager($this->mockEntityManager);

        $user->modifyUser('1c410025-096e-43c8-97c5-501bc982a836', 'Loos', 'Ging', new \DateTime('01/02/1000'));
    }

    public function testDeleteUser()
    {
        $user = new User('1c410025-096e-43c8-97c5-501bc982a836', 'Sool', 'King', new \DateTime('01/02/1993'));

        $this->mockEntityManager
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->mockObjectRepository);

        $this->mockObjectRepository
            ->expects($this->once())
            ->method('find')
            ->willReturn($user);

        $this->mockEntityManager
            ->expects($this->once())
            ->method('remove');

        $this->mockEntityManager
            ->expects($this->once())
            ->method('flush');

        $userManager = new UserManager($this->mockEntityManager);

        $userManager->deleteUser('1c410025-096e-43c8-97c5-501bc982a836');
    }

    public function testLoadUser()
    {
        $user = new User('1c410025-096e-43c8-97c5-501bc982a836', 'Sool', 'King', new \DateTime('01/02/1993'));
        $mockUser = $this->createMock(User::class);
        $mockComment = $this->createMock(Comment::class);
        $expected = ['firstname'=>'Sool', 'lastname'=>'King','comments'=> []];

        $this->mockEntityManager
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->mockObjectRepository);

        $this->mockObjectRepository
            ->expects($this->once())
            ->method('find')
            ->willReturn($user);

        /*$mockUser
            ->expects($this->once())
            ->method('getFirstname')
            ->willReturn('Sool');

        $mockUser
            ->expects($this->once())
            ->method('getLastname')
            ->willReturn('King');

        $mockUser
            ->expects($this->once())
            ->method('getComments')
            ->willReturn($mockComment);*/


        $userManager = new UserManager($this->mockEntityManager);

        $actual = $userManager->loadUser('1c410025-096e-43c8-97c5-501bc982a836');

        $this->assertEquals($expected, $actual);

        $this->assertEquals('Sool', $user->getFirstname());
        $this->assertEquals('King', $user->getLastname());
        $this->assertEquals('1c410025-096e-43c8-97c5-501bc982a836', $user->getId());
    }

    /*public function testLoadAllUser()
    {
        $mockUser =$this->createMock(User::class);

        $user = new User('1c410025-096e-43c8-97c5-501bc982a836', 'Sool', 'King', new \DateTime('01/02/1993'));

        $expected = ['id'=>'1c410025-096e-43c8-97c5-501bc982a836', 'firstname'=>'Sool', 'lastname'=>'King','birthday'=> new \DateTime('01/02/1993'), 'comments'=>[]];

        $this->mockEntityManager
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->mockObjectRepository);

        $this->mockObjectRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$mockUser]);

        $mockUser
            ->expects($this->once())
            ->method('getFirstname')
            ->willReturn('Sool');

        $mockUser
            ->expects($this->once())
            ->method('getlastname')
            ->willReturn('King');

        $mockUser
            ->expects($this->once())
            ->method('getBirthday')
            ->willReturn(new \DateTime('01/02/1993'));

        $userManager = new UserManager($this->mockEntityManager);

        $userManager->loadAllUser();





    }*/


}