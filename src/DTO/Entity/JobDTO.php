<?php

namespace App\DTO\Entity;

use Symfony\Component\HttpFoundation\File\File;

final class JobDTO implements EntityDTOInterface
{
    private string $name;
    private File $icon;
    private string $color;

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
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

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getColor(): string
    {
        return $this->color;
    }
}
