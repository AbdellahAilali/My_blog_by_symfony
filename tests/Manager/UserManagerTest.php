<?php
/**
 * Created by PhpStorm.
 * User: abdellah
 * Date: 14/08/18
 * Time: 10:29
 */

namespace Test\Manager;


use App\Entity\User;
use App\Manager\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class UserManagerTest extends TestCase
{
    public function createUserTest()
    {
        $mockEntityManager = $this->createMock(EntityManagerInterface::class);

        $mockEntityManager
            ->expects($this->once())
            ->method('persist');

        $mockEntityManager
            ->expects($this->once())
            ->method('flush');

        $actual = new UserManager($mockEntityManager);

        $actual->createUser('0101-0202-0303', 'Jane', 'Doe', new\DateTime('2000-01-01'));


    }

}