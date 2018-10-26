<?php
/**
 * Created by PhpStorm.
 * User: abdellah
 * Date: 25/10/18
 * Time: 17:04
 */

namespace Test\Manager;


use App\Entity\Comment;
use App\Entity\User;
use App\Manager\CommentManager;
use App\Manager\UserManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use PHPUnit\Framework\TestCase;

class CommentManagerTest extends TestCase
{

    private $mockEntityManager;

    private $mockObjectRepository;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->mockEntityManager = $this->createMock(EntityManagerInterface::class);
        $this->mockObjectRepository = $this->createMock(ObjectRepository::class);
    }

    public function testCreateComment()
    {
        $user = new User('1c410025-096e-43c8-97c5-501bc982a836', 'Sool', 'King', new \DateTime('01/02/1993'));
        $this->mockEntityManager
            ->expects($this->once())
            ->method('persist');

        $this->mockEntityManager
            ->expects($this->once())
            ->method('flush');

        $commentManager = new CommentManager($this->mockEntityManager);

        $commentManager->createComment('43c8-97c5-096e-1c410025-501bc982a836', 'DevTitle', 'Mon super taf de dev', $user);
    }

    public function testModifyComment()
    {
        $mockComment= $this->createMock(Comment::class);

        $user = new User('1c410025-096e-43c8-97c5-501bc982a836', 'Sool', 'King', new \DateTime('01/02/1993'));

        $this->mockEntityManager
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->mockObjectRepository);

        $this->mockObjectRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->willReturn($mockComment);

        $mockComment
            ->expects($this->once())
            ->method('update');

        $this->mockEntityManager
            ->expects($this->once())
            ->method('persist');

        $this->mockEntityManager
            ->expects($this->once())
            ->method('flush');

        $commentManager = new CommentManager($this->mockEntityManager);

        $commentManager->modifyComment('3c8-97c5-096e-1c410025-501bc982a836','NewDevTitle', 'Mon new super taf de Dev');
    }

    public function testDeleteComment()
    {
        $user = new User('1c410025-096e-43c8-97c5-501bc982a836', 'Sool', 'King', new \DateTime('01/02/1993'));

        $comment = new Comment('3c8-97c5-096e-1c410025-501bc982a836','NewDevTitle', 'Mon new super taf de Dev',$user);

        $this->mockEntityManager
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->mockObjectRepository);

        $this->mockObjectRepository
            ->expects($this->once())
            ->method('find')
            ->willReturn($comment);
    }
}