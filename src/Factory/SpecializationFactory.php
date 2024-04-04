<?php

namespace App\Factory;

use App\DTO\Entity\EntityDTOInterface;
use App\DTO\Entity\SpecializationDTO;
use App\Entity\Specialization;
use App\Util\File\FileManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final readonly class SpecializationFactory implements FactoryInterface
{
    public function __construct(
        private FileManager $fileManager,
        private ParameterBagInterface $parameterBag
    ) {}

    /**
     * @param SpecializationDTO $dto
     */
    public function create(EntityDTOInterface $dto): Specialization
    {
        return (new Specialization())
            ->setName($dto->getName())
            ->setJob($dto->getJob())
            ->setIcon($this->fileManager->uploadFile(
                $dto->getIcon(),
                $this->parameterBag->get('icon_directory')
            ))
        ;
    }
}
