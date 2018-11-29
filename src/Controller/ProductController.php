<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\BrochureType;
use App\Manager\ProductManager;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @var EntityManagerInterface $entityManager
     */
    protected $entityManager;
    /**
     * @var ProductManager
     */
    private $productManager;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ProductManager         $productManager
     */
    public function __construct(EntityManagerInterface $entityManager, ProductManager $productManager)
    {
        $this->entityManager = $entityManager;
        $this->productManager = $productManager;
    }

    /**
     * @Route("/product", name="product")
     *
     * @param Request      $request
     * @param FileUploader $fileUploader
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function uploadFileAction(Request $request, FileUploader $fileUploader)
    {
        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */

        $form = $this->createForm(BrochureType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Product $product */
            $product = $form->getData();

            $file = $product->getBrochure();
            $fileName = $fileUploader->upload($file);
            $product->setBrochure($fileName);

            $this->productManager->create($product);

            //return new Response('ok soumission du form');
            return $this->redirect($this->generateUrl('product', ["file"=>$file]));
        } /*else {echo $form->getErrors(); }*/

        //return new Response('pas de soumission du form');
        return $this->render('product/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return string
     */
    public function generateUniqueFilename()
    {
        return md5(uniqid());
    }
}
