<?php

namespace Test\Controller;

use App\Controller\CacheController;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\CacheItem;

class CacheControllerTest extends TestCase
{
    public function testCacheAction()
    {
        $mockRedisAdapter = $this->createMock(RedisAdapter::class);
       // $mockObjectCacheItem = $this->createMock(CacheItem::class);
        $mockManagerRegistry = $this->createMock(ManagerRegistry::class);
        $mockRepository = $this->createMock(ObjectRepository::class);
        $mockAbstractController = $this->createMock(AbstractController::class);
        $mockEntityRepository = $this->createMock(EntityRepository::class);
        $mockQueryBuilder = $this->createMock(QueryBuilder::class);
        $mockAbstractQuery = $this->createMock(AbstractQuery::class);

        $user = new User('0a2b3c4d-5e6f7g8h', 'Doe', 'John', new \DateTime('01/01/1999'));

        $mockRedisAdapter
            ->expects($this->once())
            ->method('getItem');

        /*$mockObjectCacheItem
            ->expects($this->once())
            ->method('isHit')
            ->willReturn(true);*/

        $mockAbstractController
            ->expects($this->once())
            ->method('getDoctrine');
           // ->willReturn($mockManagerRegistry);

        $mockEntityRepository
            ->expects($this->once())
            ->method('createQueryBuilder')
            ->willReturn($mockQueryBuilder);

        $mockQueryBuilder
            ->expects($this->once())
            ->method('getQuery')
            ->willReturn($mockQueryBuilder);

        $mockAbstractQuery
            ->expects($this->once())
            ->method('getScalarResult')
            ->willReturn(array());

     /*   $mockObjectCacheItem
            ->expects($this->once())
            ->method('set')*/

        $cacheController = new CacheController();

        $cacheController->CacheAction();


    }
}