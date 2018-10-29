<?php

namespace Test\Manager;

use App\Entity\Comment;
use App\Entity\User;
use App\Manager\CommentManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

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
        $mockComment = $this->createMock(Comment::class);

        $this->mockEntityManager
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->mockObjectRepository);

        $this->mockObjectRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['id'=> '3c8-97c5-096e-1c410025-501bc982a836'])
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

        $commentManager->modifyComment('3c8-97c5-096e-1c410025-501bc982a836', 'NewDevTitle', 'Mon new super taf de Dev');
    }

    public function testDeleteComment()
    {
        $user = new User('1c410025-096e-43c8-97c5-501bc982a836', 'Sool', 'King', new \DateTime('01/02/1993'));

        $comment = new Comment('3c8-97c5-096e-1c410025-501bc982a836', 'NewDevTitle', 'Mon new super taf de Dev', $user);

        $this->mockEntityManager
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->mockObjectRepository);

        $this->mockObjectRepository
            ->expects($this->once())
            ->method('find')
            ->with('3c8-97c5-096e-1c410025-501bc982a836')
            ->willReturn($comment);

        $this->mockEntityManager
            ->expects($this->once())
            ->method('remove');

        $this->mockEntityManager
            ->expects($this->once())
            ->method('flush');

        $comment = new CommentManager($this->mockEntityManager);

        $actual = $comment->deleteComment('3c8-97c5-096e-1c410025-501bc982a836');

        $this->assertInstanceOf(JsonResponse::class, $actual);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testDeleteCommentError()
    {
        $this->mockEntityManager
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->mockObjectRepository);

        $this->mockObjectRepository
            ->expects($this->once())
            ->method('find')
            ->with('010101010101')
            ->willReturn(null);

        $commentManager = new CommentManager($this->mockEntityManager);

        $commentManager->deleteComment('010101010101');
    }
}




