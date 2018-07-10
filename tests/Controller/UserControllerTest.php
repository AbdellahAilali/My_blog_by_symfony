<?php

namespace App\test;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class UserControllerTest extends WebTestCase
{


    public function testloadUserAction()
    {
        $client = static::createClient();

        $client->request('GET', '/user/2');

        $this->assertSame(200, $client->getResponse()->getStatusCode());


    }




    /*public function testLoadUserAction()
    {
        $response = '{"lastname":null,"firstname":null,"comments":[]}';


        $doublurBdd = $this->createMock(\Doctrine\ORM\EntityManagerInterface::class);

        $doubleRepo = $this->createMock(\Doctrine\Common\Persistence\ObjectRepository::class);


        $doublurBdd
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($doubleRepo);

        //$doublureUser = $this->createMock(\App\Entity\User::class);

        $doubleRepo
            ->expects($this->once())
            ->method('findOneBy')
            ->willReturn(new \App\Entity\User());




        $obj = new UserController($doublurBdd);

        $content = $obj->loadUserAction(1)->getContent();

        $this->assertEquals($response, $content);



    }*/

}