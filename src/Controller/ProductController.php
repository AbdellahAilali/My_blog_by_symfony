<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
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

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/product", name="product")
     *
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request, FileUploader $fileUploader)
    {
        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */

        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$file = $product->getBrochure();
            $file = $form->get('brochure')->getData();
            $fileName = $fileUploader->upload($file);

            $product->setBrochure($fileName);

            $this->entityManager->persist($product);
            $this->entityManager->flush();

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

    /**
     * @Route("/successUpload", name="success_upload")
     */
    public function SuccessUpload()
    {
        return $this->render('base.html.twig');
    }
}
