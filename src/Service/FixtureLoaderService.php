<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class FixtureLoaderService
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function load($fixturePath)
    {
        //DIR specisie le fichier sur lequel je suis.
        $loader = new \Nelmio\Alice\Loader\NativeLoader();
        $objectSet = $loader->loadFile(__DIR__ . $fixturePath)->getObjects();

        foreach ($objectSet as $object) {
            $this->manager->persist($object);
        }

        $this->manager->flush();
        $this->manager->clear();
    }
}