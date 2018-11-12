<?php

namespace App\Controller;

use PHPUnit\Util\Filesystem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    /**
     * @Route("/hello", name="hello")
     */
    public function helloIndex()
    {
        /** @var FilesystemCache $cache */

        /*$cache = new FilesystemCache();

        $cache->set('lastName', "Abdellah");

         if (!$cache->has('lastNam')) {

             echo "non";
         }

        //$user = $cache->get('lastName');

        $cache->setMultiple([
            "lastName" => "Doe",
            "firstName" => "John",
        ]);

        $users = $cache->getMultiple(["lastName", "firstName"]);

        foreach ($users as $user) {

            var_dump($user  ) ;
        }

        $cache->deleteMultiple(["lastName", "firstName"]);*/

        /**@var FilesystemAdapter $cache */
        $cache = new Filesystem();
        $redisCache = new RedisAdapter();

        $user = $cache->getItem("lastName");

        $user->set("Daryle");

        $cache->save($user);

        //$user = $cache->getItem("lastName");

        if (!$user->isHit()) {

            echo "n'existe pas dans le cache";
        }

        $total =  $user->get();
        $cache->deleteItem("lastName");

        var_dump($total);

        return $this->render('base.html.twig');
    }
}
