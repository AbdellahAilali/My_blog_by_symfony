<?php

namespace App\Manager;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class ProductManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Product $product
     */
    public function create(Product $product)
    {
        $this->entityManager->persist($product);

        $this->entityManager->flush();
    }


}