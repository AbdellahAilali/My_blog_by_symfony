<?php
/**
 * Created by PhpStorm.
 * User: abdellah
 * Date: 06/11/18
 * Time: 18:09
 */

namespace App\Controller;

use App\Salam\Hello;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController
{
    /**
     * @Route("/hello", name="hello")
     */
    public function helloIndex(Hello $hello)
    {
        return new Response($hello->berbere());
    }
}
