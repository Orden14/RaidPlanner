<?php

namespace App\Factory;

use App\DTO\Entity\EntityDTOInterface;
use App\DTO\Entity\JobDTO;
use App\Entity\Job;
use App\Util\File\FileManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final readonly class JobFactory implements FactoryInterface
{
    public function __construct(
        private FileManager $fileManager,
        private ParameterBagInterface $parameterBag
    ) {}

    /**
     * @param JobDTO $dto
     */
    public function create(EntityDTOInterface $dto): Job
    {
        return (new Job())
            ->setName($dto->getName())
            ->setIcon($this->fileManager->uploadFile(
                $dto->getIcon(),
                $this->parameterBag->get('icon_directory')
            ))
            ->setColor($dto->getColor())
        ;
    }
}
