<?php

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
    public $adapter;

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @BeforeScenario
     */
    public function setUp()
    {
        $kernel = $this->getKernel();
        $em = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $this->createDb($em);

        //DIR specisie le fichier sur lequel je suis.
        $loader = new \Nelmio\Alice\Loader\NativeLoader();
        $objectSet = $loader->loadFile(__DIR__ . '/../../tests/Fixtures/fixtures.yml')->getObjects();

        foreach ($objectSet as $object) {
            $em->persist($object);
        }

        $em->flush();
        $em->clear();

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
     * @throws Exception
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
     * @throws Exception
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
     * @throws Exception
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

       var_dump($client->getRequest()->);
    }

}

