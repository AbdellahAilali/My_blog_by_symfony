<?php

namespace Test\Manager;

use App\Entity\User;
use App\Manager\UserManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

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

        $userManager->createUser('1c410025-096e-43c8-97c5-501bc982a836', 'Sool', 'King', new \DateTime('01/01/1993'));
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
            ->with(['id' => '1c410025-096e-43c8-97c5-501bc982a836'])
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
            ->with('1c410025-096e-43c8-97c5-501bc982a836')
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

    /**
     * @expectedException  \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testDeleteUserError()
    {
        /*$this->expectException(NotFoundHttpException::class);*/

        $this->mockEntityManager
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->mockObjectRepository);

        $this->mockObjectRepository
            ->expects($this->once())
            ->method('find')
            ->with('096e-43c8-97c5-501bc982a836')
            ->willReturn(null);

        $userManager = new UserManager($this->mockEntityManager);
        $userManager->deleteUser('096e-43c8-97c5-501bc982a836');
    }

    public function testLoadUser()
    {
        $user = new User('1c410025-096e-43c8-97c5-501bc982a836', 'Sool', 'King', new \DateTime('01/02/1993'));
        $expected = ['firstname' => 'Sool', 'lastname' => 'King', 'comments' => []];

        $this->mockEntityManager
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->mockObjectRepository);

        $this->mockObjectRepository
            ->expects($this->once())
            ->method('find')
            ->with('1c410025-096e-43c8-97c5-501bc982a836')
            ->willReturn($user);

        $userManager = new UserManager($this->mockEntityManager);

        $actual = $userManager->loadUser('1c410025-096e-43c8-97c5-501bc982a836');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testLoadUserError()
    {
        $this->mockEntityManager
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->mockObjectRepository);

        $this->mockObjectRepository
            ->expects($this->once())
            ->method('find')
            ->with('0101010101')
            ->willReturn(null);

        $userManager = new UserManager($this->mockEntityManager);
        $userManager->loadUser('0101010101');
    }

    public function testLoadAllUser()
    {
        $user = new User('1c410025-096e-43c8-97c5-501bc982a836', 'Sool', 'King', new \DateTime('01/02/1993'));

        $expected = array(['id' => '1c410025-096e-43c8-97c5-501bc982a836', 'firstname' => 'Sool', 'lastname' => 'King', 'birthday' => '1993-01-02']);

        $this->mockEntityManager
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->mockObjectRepository);

        $this->mockObjectRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$user]);

        $userManager = new UserManager($this->mockEntityManager);

        $actual = $userManager->loadAllUser();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testLoadAllUserError()
    {
        $this->mockEntityManager
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->mockObjectRepository);

        $this->mockObjectRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $userManager = new UserManager($this->mockEntityManager);
        $userManager->loadAllUser();

    }


}