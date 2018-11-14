<?php

namespace Test\Controller;

use App\Controller\CacheController;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\HttpFoundation\Response;
use Twig\Template;

class CacheControllerTest extends TestCase
{
    public function testCachedDataIsNot()
    {
        $mockEntityRepository   = $this->createMock(EntityRepository::class);
        $mockEntityManager      = $this->createMock(EntityManagerInterface::class);
        $mockAbstractQuery      = $this->createMock(AbstractQuery::class);
        $mockQueryBuilder       = $this->createMock(QueryBuilder::class);
        $mockResponse           = $this->createMock(Response::class);

        $mockCacheItemFinalClass = \Mockery::mock(new CacheItem());
        $mockCacheItemFinalClass
            ->shouldReceive('getItem')
            ->andReturn($mockCacheItemFinalClass);

        $mockCacheItemFinalClass
            ->shouldReceive('isHit')
            ->andReturn([false]);

        $mockEntityManager
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($mockEntityRepository);

        $mockEntityRepository
            ->expects($this->once())
            ->method('createQueryBuilder')
            ->with('u')
            ->willReturn($mockQueryBuilder);

        $mockQueryBuilder
            ->expects($this->once())
            ->method('getQuery')
            ->willReturn($mockAbstractQuery);

        $mockAbstractQuery
            ->expects($this->once())
            ->method('getScalarResult')
            ->willReturn(array());

        $mockCacheItemFinalClass
            ->shouldReceive('set')
            ->with([])
            ->andReturn([]);

        $mockCacheItemFinalClass
            ->shouldReceive('save')
            ->with($mockCacheItemFinalClass)
            ->andReturn(true);

        $mockTwigTemplate = $this->createMock(Template::class);
        $mockTwigTemplate
            ->expects($this->once())
            ->method('render')
            ->with(['base.html.twig'])
            ->willReturn([$mockResponse]);

        $cacheController = new CacheController($mockEntityManager, $mockTwigTemplate);

        $cacheController->cachedData();
    }


    public function testCachedData()
    {
        $mockEntityManager = $this->createMock(EntityManagerInterface::class);
        $mockTwigTemplate  = $this->createMock(Template::class);
        $mockResponse      = $this->createMock(Response::class);

        $mockCacheItemFinalClass = \Mockery::mock(new CacheItem());

        $mockCacheItemFinalClass
            ->expects($this->once())
            ->andReturn([$mockCacheItemFinalClass]);

        $mockCacheItemFinalClass
            ->shouldReceive('isHit')
            ->andReturn([true]);

        $mockTwigTemplate
            ->expects($this->once())
            ->method('render')
            ->willReturn([$mockResponse]);

        $cacheController = new CacheController($mockEntityManager, $mockTwigTemplate);

        $cacheController->cachedData();

    }

}