<?php

use App\Service\FixtureLoaderService;
use Behat\Behat\Context\Context;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpKernel\Client;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, KernelAwareContext
{
    use KernelDictionary;

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var FixtureLoaderService
     */
    private $fixtureLoaderService;

    /**
     * @var EntityManager
     */
    private $entityManager;


    public function __construct(
        AdapterInterface $adapter,
        EntityManager $entityManager,
        FixtureLoaderService $fixtureLoaderService)
    {
        $this->adapter = $adapter;
        $this->fixtureLoaderService = $fixtureLoaderService;
        $this->entityManager = $entityManager;
    }

    /**
     * @BeforeScenario
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    public function setUp()
    {
        $this->createDb($this->entityManager);

        $this->fixtureLoaderService->load('/../../tests/Fixtures/fixtures.yml');

        $this->adapter->clear();
    }

    /**
     * @AfterScenario
     *
     * @param EntityManager $em
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    private function createDb(EntityManager $em)
    {
        $tool = new \Doctrine\ORM\Tools\SchemaTool($em);
        $classes = [];
        /** @var ClassMetadata $class */

        foreach ($em->getMetadataFactory()->getAllMetadata() as $class) {
            $classes[] = $class;
        }

        $tool->dropSchema($classes);
        $tool->createSchema($classes);
        $tool->updateSchema($classes);
    }


    /**
     * @Given users are not cached
     *
     * @throws InvalidArgumentException
     */
    public function ItemIsNotCached()
    {
        $userItem = $this->adapter->getItem('users');
        if ($userItem->isHit()) {
            throw new \Exception('users are cached !');
        }
    }

    /**
     * @Then users are in cache
     *
     * @throws InvalidArgumentException
     */
    public function ItemIsCached()
    {
        $userItem = $this->adapter->getItem('users');

        if (!$userItem->isHit()) {
            throw new \Exception('users are not cached !');
        }
    }

    /**
     * @Given  save users in cache
     *
     * @throws InvalidArgumentException
     */
    public function ItemIsAlreadyCached()
    {
        $userItem = $this->adapter->getItem('users');

        $userItem->set('Ailali,Abdellah');

        $this->adapter->save($userItem);
    }

    /**
     * @Then doctrine doesn't not query
     */
    public function doctrineDoesntQuery()
    {
        $kernel = $this->getKernel();

        /**@var Client $client */
        $client = $kernel->getContainer()->get('test.client');

        $client->request("GET", "/cached");

    }
}
