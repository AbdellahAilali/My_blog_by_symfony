<?php

namespace Test\Controller;

use App\Controller\ProductController;
use App\Entity\Product;
use App\Form\ProductType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class ProductControllerTest extends TestCase
{
    public function testUploadFileAction()
    {
        $mockProductType = $this->createMock(ProductType::class);
        $mockRequest = $this->createMock(Request::class);
        $mockFileUploader = $this->createMock(FileUploader::class);
        $mockEntityManager = $this->createMock(EntityManagerInterface::class);
        $mockFormInterface = $this->createMock(FormInterface::class);

        $product = new Product();
        $file = $product->setBrochure('image.png');

        $mockFormInterface
            ->expects($this->once())
            ->method('handleRequest')
            ->willReturn($file);

        $mockFormInterface
            ->expects($this->once())
            ->method('isSubmitted')
            ->willReturn(true);

        $mockFormInterface
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $fileProduct = new ProductController($mockEntityManager);

        $fileProduct->uploadFileAction($mockRequest, $mockFileUploader);
    }
}