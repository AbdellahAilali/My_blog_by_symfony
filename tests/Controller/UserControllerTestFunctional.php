<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTestFunctional extends WebTestCase
{
    public function testLoadUserAction()
    {
        $client = static::createClient();

        $client->request("GET","/user/2");

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }


    public function testDeleteUser()
    {
        $client = static::createClient();

        // charger les fixture

        $loader = new \Nelmio\Alice\Loader\NativeLoader();
        $objectSet = $loader->loadFile(__DIR__ . '/../Fixtures/fixtures.yml')->getObjects();

        foreach($objectSet as $object) {
            self::$kernel->getContainer()->get('doctrine.orm.entity_manager')->persist($object);
        }

        self::$kernel->getContainer()->get('doctrine.orm.entity_manager')->flush();

        $client->request("DELETE","/user/Ailali");

        $this->assertSame(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());

    }



}