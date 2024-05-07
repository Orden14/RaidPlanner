<?php

namespace App\Entity;

use App\Entity\Trait\BuildRelationalPropertiesTrait;
use App\Enum\BuildStatusEnum;
use App\Repository\BuildRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BuildRepository::class)]
class Build
{
    use BuildRelationalPropertiesTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $link = null;

    #[ORM\Column]
    private string $status = BuildStatusEnum::META->value;

    #[ORM\Column]
    private ?DateTime $lastEditedAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $benchmark = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $benchmarkLink = null;

    final public function getId(): ?int
    {
        return $this->id;
    }

    final public function getName(): ?string
    {
        return $this->name;
    }

    final public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    final public function getLink(): ?string
    {
        return $this->link;
    }

    final public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    final public function getStatus(): string
    {
        return $this->status;
    }

    final public function setStatus(BuildStatusEnum $status): self
    {
        $this->status = $status->value;

        return $this;
    }

    final public function getLastEditedAt(): ?DateTime
    {
        return $this->lastEditedAt;
    }

    final public function setLastEditedAt(DateTime $lastEditedAt): self
    {
        $this->lastEditedAt = $lastEditedAt;

        return $this;
    }

    final public function getBenchmark(): ?int
    {
        return $this->benchmark;
    }

    final public function setBenchmark(?int $benchmark): self
    {
        $this->benchmark = $benchmark;

        return $this;
    }

    final public function getBenchmarkLink(): ?string
    {
        return $this->benchmarkLink;
    }

    final public function setBenchmarkLink(?string $benchmarkLink): self
    {
        $this->benchmarkLink = $benchmarkLink;

        return $this;
    }
}
