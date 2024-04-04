<?php

namespace App\DataFixtures\util;

use Symfony\Component\HttpFoundation\File\File;

final class FileMockUploader
{
    public function mockFileUpload(string $filename): File
    {
        $filePath = __DIR__ . DIRECTORY_SEPARATOR . 'icon' . DIRECTORY_SEPARATOR . $filename . '.png';
        $tempPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . basename($filePath);
        copy($filePath, $tempPath);

        return new File($tempPath, true);
    }
}
