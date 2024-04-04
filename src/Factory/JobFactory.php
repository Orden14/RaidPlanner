<?php

namespace App\Factory;

use App\DTO\Entity\EntityDTOInterface;
use App\DTO\Entity\JobDTO;
use App\Entity\Job;
use App\Util\File\FileManager;

final readonly class JobFactory implements FactoryInterface
{
    public function __construct(
        private FileManager $fileManager,
    ) {}

    /**
     * @param JobDTO $dto
     */
    public function create(EntityDTOInterface $dto): Job
    {
        return (new Job())
            ->setName($dto->getName())
            ->setIcon($this->fileManager->uploadFile($dto->getIcon(), 'icons'))
            ->setColor($dto->getColor())
        ;
    }
}
