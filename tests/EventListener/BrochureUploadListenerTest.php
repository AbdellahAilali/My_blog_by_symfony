<?php

namespace App\EventListener;

use App\Service\FileUploader;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use PHPUnit\Framework\TestCase;

class BrochureUploadListenerTest extends TestCase
{
    public function TestPrePersist()
    {
        $mockLifecycleEventArgs = $this->createMock(LifecycleEventArgs::class);
        $mockBrochureUploadListener = $this->createMock(BrochureUploadListener::class);
        $mockFileUploader = $this->createMock(FileUploader::class);

        $mockLifecycleEventArgs
            ->expects($this->once())
            ->method('getEntity');

        $mockBrochureUploadListener
            ->expects($this->once())
            ->method('uploadFile');

        $FileUploaderObject = new BrochureUploadListener('image');

       $actua =  $FileUploaderObject->prePersist($mockFileUploader);

        $this->assertNull($actua);
    }
}