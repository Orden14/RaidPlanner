<?php

namespace App\DataFixtures\util;

use App\Util\File\FileManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\KernelInterface;

final readonly class FileMockUploader
{
    public function __construct(
        private KernelInterface $kernel,
        private FileManager $fileManager,
        private ParameterBagInterface $parameterBag,
    ) {
    }

    public function mockFileUpload(string $filename): string
    {
        if ($this->kernel->getEnvironment() !== 'dev') {
            return 'emptyFileForTest.png';
        }

        $filePath = __DIR__ . DIRECTORY_SEPARATOR . 'icon' . DIRECTORY_SEPARATOR . $filename . '.png';
        $tempPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . basename($filePath);
        copy($filePath, $tempPath);

        return $this->fileManager->uploadFile(
            new File($tempPath, true),
            $this->parameterBag->get('icon_directory')
        );
    }
}
