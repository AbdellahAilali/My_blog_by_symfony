<?php

namespace Test\Controller;

use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Common\Persistence\PersistentObject;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Http\Client\Curl\Client;
use PHPUnit\Util\Json;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CommentControllerFunctionalTest extends WebTestCase
{
    public function testCreateCommentFunctionalAction()
    {
        /**
         * @var EntityManagerInterface $em
         * @var Client $client
         */
        $em = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');

        $client = static::createClient();

        $client->request("POST", "/comment", array(), array(),
            array(), '{"title":"la decouvert du continent ameriquain","description":"par dessus les collines et les riviere","user":"32132dsf132ds1f3ds21fsd"}', true);

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $content = json_decode($client->getResponse()->getContent(), true);

        $comment = $em->getRepository(Comment::class)->find($content['id']);

        $this->assertInstanceOf(JsonResponse::class, $client->getResponse());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("la decouvert du continent ameriquain", $comment->getTitle());
        $this->assertEquals("par dessus les collines et les riviere", $comment->getDescription());
        $this->assertEquals("32132dsf132ds1f3ds21fsd", $comment->getUser()->getId());
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"' // optional message shown on failure
        );
    }

    public function testCreateCommentFunctionalActionError()
    {
        /**
         * @var Client $client
         */
        $client = static::createClient();

        $client->request('POST', '/comment');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertInstanceOf(JsonResponse::class, $client->getResponse());
    }

    public function testModifyCommentFunctionalAction()
    {
        /**
         * @var EntityManagerInterface $em
         * @var Client $client
         */

        $em = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');

        $client = static::createClient();

        $client->request("PUT", "/modify_comment/654984ds65f1d651f6s5d1f", array(), array(), array(), '{"title":"roots","description":"les decouvertes africaine dans le temps"}', true);

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $content = json_decode($client->getResponse()->getContent(), true);

        $comment = $em->getRepository(Comment::class)->find($content['id']);

        $this->assertEquals("roots", $comment->getTitle());
        $this->assertEquals("les decouvertes africaine dans le temps", $comment->getDescription());
        $this->assertEquals("32132dsf132ds1f3ds21fsd", $comment->getUser()->getId());

    }

    public function testModifyCommentFunctionalActionError()
    {
        $client = static::createClient();

        $client->request('POST', '/comment');

        //dump(json_decode($client->getResponse()->getContent(), true)[0]);

        $this->assertEquals(400, $client->getResponse()->getStatusCode());

    }

    public function testDeleteCommentFunctionalAction()
    {
        $client = self::$kernel->getContainer()->get('test.client');

        $client->request("DELETE", "/delete_comment/654984ds65f1d651f6s5d1f");

        $this->assertSame(200, $client->getResponse()->getStatusCode());

    }

    protected function setUp()
    {
        /** @var PersistentObject persist,flush */
        /** @var EntityManager $em */

        static::createClient();

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