<?php

namespace App\DTO\Entity;

use App\Entity\Job;
use Symfony\Component\HttpFoundation\File\File;

final class SpecializationDTO implements EntityDTOInterface
{
    private string $name;
    private Job $job;
    private File $icon;

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
