<?php

use Behat\Behat\Context\Context;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\HttpKernel\KernelInterface;
use \Behat\Symfony2Extension\Context\KernelDictionary;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, KernelAwareContext
{
    use KernelDictionary;

    /**
     * @BeforeScenario
     */
    public function setUp()
    {
        echo '--------------------------------------------';

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
    }

    /**
     * @AfterScenario
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
    }

}

