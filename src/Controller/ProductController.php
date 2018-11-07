<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Manager\ProductManager;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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


    public function __construct(EntityManagerInterface $entityManager, ProductManager $productManager)
    {
        $this->entityManager = $entityManager;
        $this->productManager = $productManager;
    }

//    public function setHello($hello)
//    {
//        $this->hello = $hello;
//    }

    /**
     * @Route("/product", name="product")
     *
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function uploadFileAction(Request $request, FileUploader $fileUploader)
    {
        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */

        $form = $this->createForm(ProductType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Product $product */
            $product = $form->getData();

            $file = $product->getBrochure();
            $fileName = $fileUploader->upload($file);
            $product->setBrochure($fileName);

            $this->productManager->create($product);

            return $this->redirect($this->generateUrl('success_upload'));
        }

        return $this->render('product/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function generateUniqueFilename()
    {
        return md5(uniqid());
    }

}
