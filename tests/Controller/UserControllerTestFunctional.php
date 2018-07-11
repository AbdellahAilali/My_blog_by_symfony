<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTestFunctional extends WebTestCase
{
    public function testLoadUserAction()
    {
        $client = static::createClient();

        $client->request("GET","/user/1");

        $this->assertSame(404, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());

    }



}