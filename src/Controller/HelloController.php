<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    /**
     * @Route("/hello", name="hello")
     */
    public function helloIndex()
    {
        $cache = new FilesystemCache();

        $cache->set('stats.products_cont', 4711);

        if ($cache->has('stats.products_cont')) {
            return $cache;
        }
        var_dump($cache);
        return $this->render('base.html.twig');
    }
}
