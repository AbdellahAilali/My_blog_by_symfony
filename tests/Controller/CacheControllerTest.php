<?php

namespace Test\Controller;

use App\Controller\CacheController;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
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
        $mockAdapterInterface   = $this->createMock(AdapterInterface::class);
        $mockCacheItemInterface = $this->createMock(CacheItemInterface::class);

        $mockAdapterInterface
            ->expects($this->once())
            ->method('getItem')
            ->with('users')
            ->willReturn($mockCacheItemInterface );

        $mockCacheItemInterface
            ->expects($this->once())
            ->method('isHit')
            ->willReturn(false);

        $mockEntityManager
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($mockEntityRepository);

        $mockEntityRepository
            ->expects($this->once())
            ->method('createQueryBuilder')
            ->willReturn($mockQueryBuilder);

        $mockQueryBuilder
            ->expects($this->once())
            ->method('getQuery')
            ->willReturn($mockAbstractQuery);

        $mockAbstractQuery
            ->expects($this->once())
            ->method('getScalarResult')
            ->willReturn(array());

        $mockCacheItemInterface
            ->expects($this->once())
            ->method('set')
            ->willReturn([]);

        $mockAdapterInterface
            ->expects($this->once())
            ->method('save')
            ->willReturn(true);

        $mockTwigTemplate = $this->createMock(Environment::class);
        $mockTwigTemplate
            ->expects($this->once())
            ->method('render')
            ->with('base.html.twig')
            ->willReturn($mockResponse);

        $cacheController = new CacheController($mockEntityManager, $mockTwigTemplate, $mockAdapterInterface);

        $actual = $cacheController->cachedData();

        $this->assertSame(200, $actual->getStatusCode());
        $this->assertInstanceOf( Response::class, $actual);
    }

    public function testCachedData()
    {
        $mockEntityManager      = $this->createMock(EntityManagerInterface::class);
        $mockTwigTemplate       = $this->createMock(Environment::class);
        $mockResponse           = $this->createMock(Response::class);
        $mockCacheItemInterface = $this->createMock(CacheItemInterface::class);
        $mockAdapterInterface   = $this->createMock(AdapterInterface::class);

        $mockAdapterInterface
            ->expects($this->once())
            ->method('getItem')
            ->with('users')
            ->willReturn($mockCacheItemInterface);

        $mockCacheItemInterface
            ->expects($this->once())
            ->method('isHit')
            ->willReturn(true);

        $mockTwigTemplate
            ->expects($this->once())
            ->method('render')
            ->willReturn($mockResponse);

        $cacheController = new CacheController(
            $mockEntityManager,
            $mockTwigTemplate,
            $mockAdapterInterface);

        $actual = $cacheController->cachedData();

        $this->assertSame(200, $actual->getStatusCode());
        $this->assertInstanceOf( Response::class, $actual);
    }

}