<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Common\Persistence\PersistentObject;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Client;

class UserControllerFunctionalTest extends WebTestCase
{
    public function testLoadAllUserFunctionalAction()
    {
        /**
         * @var Client $client
         */
        //$client = self::$kernel->getContainer()->get('test.client');
        $client = static::createClient();

        $client->request("GET", "/");

        $expected = '[{"id":"025caf9e-e6e6-4aac-a45b","firstname":"John","lastname":"Doe","birthday":"2018-10-31","comments":[{"title":"Le voyage de Chihiro","comment":"Une fillette de 10 ans, prise au pi\u00e8ge dans une maison sur la plage hantee par des esprits et des fant\u00f4mes, doit combattre sorci\u00e8res et dragons"}]},{"id":"32132dsf132ds1f3ds21fsd","firstname":"Abdellah","lastname":"Ailali","birthday":"2018-10-31","comments":[{"title":"Le ch\u00e2teau ambulant","comment":"La jeune Sophie, \u00e2g\u00e9e de 18 ans, travaille sans rel\u00e2che dans la boutique de chapelier que tenait son p\u00e8re avant de mourir. Lors de l\u0027une de ses rares sorties en ville, elle fait la connaissance de Hauru le Magicien"}]},{"id":"4eb298dd-5cd7-4d10-9b9q","firstname":"Malik","lastname":"Ben","birthday":"2018-10-31","comments":[{"title":"Kill Bill","comment":"Condamnee \u00e0 mort par son propre patron Bill, une femme-assassin survit \u00e0 une balle dans la t\u00eate.  Quatre ans plus tard elle sort du coma et jure d\u2019avoir sa vengeance.\u2026 "}]}]';

        $this->assertEquals($expected, $client->getResponse()->getContent());
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"' // optional message shown on failure
        );

    }

    public function testLoadAllUserFunctionalActionError()
    {
        /**
         * @var Client $client
         */
        $client = static::createClient();

        $client->request('GET', '/errorUri');

        $this->assertSame(405, $client->getResponse()->getStatusCode());

    }

    public function testLoadUserFunctionalAction()
    {
        /**
         * @var Client $client
         */
        $client = static::createClient();

        $client->request("GET", "/user/025caf9e-e6e6-4aac-a45b");

        $expected = '{"firstname":"John","lastname":"Doe","comments":{"title":"Le voyage de Chihiro","description":"Une fillette de 10 ans, prise au pi\u00e8ge dans une maison sur la plage hantee par des esprits et des fant\u00f4mes, doit combattre sorci\u00e8res et dragons"}}';

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $client->getResponse());
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"');
    }

    public function testLoadUserFunctionalActionError()
    {
        /**
         * @var Client $client
         */
        $client = static::createClient();

        $client->request('GET', '/user/10101');

        $messageError = '{"error_message":"User not found"}';

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals($messageError, $client->getResponse()->getContent());
        //voir en base
    }

    public function testDeleteUserFunctionalAction()
    {
        /**
         * @var Client $client
         * @var EntityManagerInterface $em
         */
        $client = static::createClient();

        $client->request("DELETE", "/025caf9e-e6e6-4aac-a45b");

        $em = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');

        $user = $em->getRepository(User::class)->find('025caf9e-e6e6-4aac-a45b');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $client->getResponse());
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"');
        $this->assertNull($user);
    }

    public function testDeleteUserFunctionalActionError()
    {
        /**
         * @var Client $client
         */
        $client = static::createClient();

        $client->request('GET', '/user/010101');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals('{"error_message":"User not found"}', $client->getResponse()->getContent());

    }

    public function testCreateUserFunctionalAction()
    {
        /**
         * @var Client $client
         * @var EntityManagerInterface $em
         */
        $em = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');

        $client = self::createClient();

        $client->request("POST", "/user", array(), array(), array(), '{"firstname":"aifaska","lastname":"karim","birthday":"2000-08-01"}', true);

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $content = json_decode($client->getResponse()->getContent(), true);

        $user = $em->getRepository(User::class)->find($content['id']);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $client->getResponse());
        $this->assertEquals("aifaska", $user->getFirstname());
        $this->assertEquals("karim", $user->getLastname());
        $this->assertEquals("2000-08-01", $user->getBirthday()->format('Y-m-d'));
    }

    public function testCreateUserFunctionalActionError()
    {
        $client = self::createClient();

        $client->request('GET', '/user', array(), array(), array(), '{"lastname":"", "firstname":"", "birthday":""}');

        $this->assertEquals(405, $client->getResponse()->getStatusCode());
    }

    public function testModifyUserFunctionalAction()
    {
        /**
         * @var Client $client
         */
        $em = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');

        $client = self::$kernel->getContainer()->get('test.client');

        $client->request("PUT", "/32132dsf132ds1f3ds21fsd", array(), array(), array(),
            '{"lastname":"Mill","firstname":"Mike",
            "birthday":"1988-08-01"}', true);

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $content = json_decode($client->getResponse()->getContent(), true);

        $user = $em->getRepository(User::class)->find($content['id']);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $client->getResponse());
        $this->assertEquals("Mill", $user->getLastname());
        $this->assertEquals("Mike", $user->getFirstname());
        $this->assertEquals("1988-08-01", $user->getBirthday()->format('Y-m-d'));

    }

    public function testModifyUserFunctionalActionError()
    {
        /**
         * @var Client $client
         */
        $client = self::createClient();

        $client->request('PUT', '/32132dsf132ds1f3ds21fsd', array(), array(), array(), '{"lastname":"","firstname":"","birthday":""}');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $client->getResponse());
    }

    protected function setUp()
    {
        /** @var PersistentObject persist,flush */

        self::createClient();

        $em = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');

        $this->createDb($em);

        $loader = new \Nelmio\Alice\Loader\NativeLoader();
        $objectSet = $loader->loadFile(__DIR__ . '/../Fixtures/fixtures.yml')->getObjects();

        foreach ($objectSet as $object) {
            $em->persist($object);
        }
        $em->flush();
        $em->clear();
        parent::setUp();
    }

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