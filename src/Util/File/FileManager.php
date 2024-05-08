<?php

namespace App\Util\File;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;

final readonly class FileManager
{
    /**
     * @throws FileException
     */
    public function uploadFile(File $file, string $directory): string
    {
        $fileName = uniqid('file', true).'.'. $file->guessExtension();

        try {
            $file->move(
                $directory,
                $fileName
            );
        } catch (FileException $e) {
            throw new FileException('An error occurred while uploading the file', $e->getCode());
        }

        return $fileName;
    }

    public function removeFile(string $fileName, string $directory): void
    {
        unlink($directory . DIRECTORY_SEPARATOR . $fileName);
    }
}
