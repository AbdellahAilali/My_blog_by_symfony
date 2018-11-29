<?php

namespace Test\Controller;

use App\Controller\ProductController;
use App\Entity\Product;
use App\Form\BrochureType;
use App\Manager\ProductManager;
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
        $mockRequest = $this->createMock(Request::class);
        $mockFileUploader = $this->createMock(FileUploader::class);
        $mockEntityManager = $this->createMock(EntityManagerInterface::class);
        $mockFormInterface = $this->createMock(FormInterface::class);
        $mockProsuctManager = $this->createMock(ProductManager::class);
        $mockProductManager = $this->createMock(ProductManager::class);
        $mockProduct = $this->createMock(Product::class);

        $mockFormInterface
            ->expects($this->once())
            ->method('handleRequest')
            ->willReturn($mockProduct);

        $mockFormInterface
            ->expects($this->once())
            ->method('isSubmitted')
            ->willReturn(true);

        $mockFormInterface
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $fileProduct = new ProductController($mockEntityManager, $mockProductManager );

        $fileProduct->uploadFileAction($mockRequest, $mockFileUploader);
    }
}