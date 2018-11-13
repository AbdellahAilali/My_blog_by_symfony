<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Symfony\Component\Routing\Annotation\Route;

class CacheController extends AbstractController
{
    /**
     * @Route("/hello", name="hello")
     */
    public function CacheAction()
    {
        /*
         * PSR 16

         $cache = new FilesystemCache();

         $cache->set('lastName', "Abdellah");

         if (!$cache->has('lastNam')) {

             echo "non";
         }

        //$user = $cache->get('lastName');

        $cache->setMultiple([HelloController
            "lastName" => "Doe",
            "firstName" => "John",
        ]);

        $users = $cache->getMultiple(["lastName", "firstName"]);

        foreach ($users as $user) {

            var_dump($user  ) ;
        }

        ////PSR 6     ITEM POOL ADAPTER

        $cache->deleteMultiple(["lastName", "firstName"]);

        $cache = new Filesystem();

        $user = $cache->getItem("lastName");

        $user->set("Daryle");

        $cache->save($user);

        //$user = $cache->getItem("lastName");

        if (!$user->isHit()) {

            echo "n'existe pas dans le cache";
        }

        $total =  $user->get();
        $cache->deleteItem("lastName");

        var_dump($total);*/

        $client = RedisAdapter::createConnection(
            'redis://localhost'
        );

        /** @var RedisAdapter $cache */
        $cache = new RedisAdapter(
            $client,
            $namespace = '',
            $defaultLifetime = 10
        );

        $userItem = $cache->getItem('users');

        if (!$userItem->isHit()) {

            echo 'Je ne suis pas en cache';

            /** @var EntityRepository $repo */
            $repo = $this->getDoctrine()
                ->getRepository(User::class);

            $users = $repo
                ->createQueryBuilder('u')
                ->getQuery()
                ->getScalarResult();

            $userItem->set(json_encode($users));
            $cache->save($userItem);

        } else {

            echo 'Je suis en cache';
        }

        //var_dump($userItem->get());

        return $this->render('base.html.twig');
    }
}
