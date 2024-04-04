<?php

namespace App\DTO\Entity;

use App\Entity\EntityInterface;
use App\Entity\Job;
use App\Entity\Specialization;
use LogicException;
use Symfony\Component\HttpFoundation\File\File;

final class SpecializationDTO implements EntityDTOInterface
{
    private string $name;
    private Job $job;
    private File $icon;

    /**
     * @param Specialization $object
     *
     * @throws LogicException
     */
    public function setFromObject(EntityInterface $object): self
    {
        $this->name = $object->getName();
        $this->job = $object->getJob();

        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setJob(Job $job): self
    {
        $this->job = $job;

        return $this;
    }

    public function getJob(): Job
    {
        return $this->job;
    }

    public function setIcon(File $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getIcon(): File
    {
        return $this->icon;
    }
}
