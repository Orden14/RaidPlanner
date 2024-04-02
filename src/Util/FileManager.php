<?php

namespace App\Util;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;

class FileManager
{
    /**
     * @throws FileException
     */
    final public function uploadFile(File $file, string $directory): string
    {
        $newFileName = uniqid('', true).'.'. $file->guessExtension();

        try {
            $file->move(
                $directory,
                $newFileName
            );
        } catch (FileException $e) {
            throw new FileException('An error occurred while uploading the file', $e->getCode());
        }

        return $newFileName;
    }

    final public function removeFile(string $fileName, string $directory): void
    {
        unlink($directory . '/' . $fileName);
    }
}
