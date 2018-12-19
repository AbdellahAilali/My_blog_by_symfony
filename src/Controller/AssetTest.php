<?php

namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Asset\Package;

class AssetTest extends Controller
{
    /**@Route("/asset", name="asset_page")
     * @param Package $package
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function testAction(Package $package)
    {
        echo $package->getUrl('/uploads/brochure/image.jpeg');
        echo $package->getUrl('uploads/brochure/image.jpeg');

        return $this->render('test.html.twig');
    }
}