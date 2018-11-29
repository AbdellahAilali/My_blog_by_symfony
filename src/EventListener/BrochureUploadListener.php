<?php

namespace App\EventListener;

use App\Entity\Product;
use App\Service\FileUploader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class BrochureUploadListener
{
    /**
     * @var FileUploader
     */
    private $uploader;

    /**
     * @param FileUploader $uploader
     */
    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    /**
     * @param $entity
     */
    private function uploadFile($entity)
    {
        if (!$entity instanceof Product) {
            return;
        }

        $file = $entity->getBrochure();

        if ($file instanceof UploadedFile) {
            $fileName = $this->uploader->upload($file);
            $entity->setBrochure($fileName);

        } elseif ($file instanceof File) {
            $entity->setBrochure($file->getFileName());
        }
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

}