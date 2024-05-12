<?php

namespace App\Entity;

use App\Entity\Trait\BuildExternalLinkPropertiesTrait;
use App\Entity\Trait\BuildRelationalPropertiesTrait;
use App\Enum\BuildStatusEnum;
use App\Repository\BuildRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BuildRepository::class)]
class Build
{
    use BuildRelationalPropertiesTrait;
    use BuildExternalLinkPropertiesTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private string $status = BuildStatusEnum::META->value;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $lastEditedAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $benchmark = null;

    public function __construct()
    {
        $this->playerSlots = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->buildMessages = new ArrayCollection();
    }

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

    final public function getStatus(): string
    {
        return $this->status;
    }

    final public function setStatus(BuildStatusEnum $status): self
    {
        $this->status = $status->value;

        return $this;
    }

    final public function getLastEditedAt(): ?DateTimeInterface
    {
        return $this->lastEditedAt;
    }

    final public function setLastEditedAt(DateTimeInterface $lastEditedAt): self
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
}
