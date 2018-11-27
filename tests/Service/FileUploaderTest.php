<?php

namespace App\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploaderTest extends TestCase
{
    public function testUpload()
    {
        $mockUploadFile = $this->createMock(UploadedFile::class);

        $mockUploadFile
            ->expects($this->once())
            ->method('guessExtension')
            ->willReturn('png');

        $mockUploadFile
            ->expects($this->once())
            ->method('move');

        $fileUploader = new FileUploader('image');

        $actual = $fileUploader->upload($mockUploadFile);

        $this->assertNotEmpty($actual);
    }

    public function testUploadError()
    {
        $mockUploadedFile = $this->createMock(UploadedFile::class);
        $mockFileException = $this->createMock(FileException::class);

        $mockUploadedFile
            ->expects($this->once())
            ->method('guessExtension')
            ->willReturn(null);

        $mockUploadedFile
            ->expects($this->once())
            ->method('move')
            ->willThrowException($mockFileException);

        $mockFileException
            ->expects($this->once())
            ->method('getMessage')
            ->willReturn('error_message');

        $fileUploader = new FileUploader('image');

        $fileUploader->upload($mockUploadedFile);

    }
}