<?php

namespace App\Util\File;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;

class FileManager
{
    /**
     * @throws FileException
     */
    final public function uploadFile(File $file, string $directory, string $definedFileName = null): string
    {
        if ($definedFileName) {
            $fileName = $definedFileName;
        } else {
            $fileName = uniqid('file', true).'.'. $file->guessExtension();
        }

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

    final public function removeFile(string $fileName, string $directory): void
    {
        unlink($directory . '/' . $fileName);
    }
}
