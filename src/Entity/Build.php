<?php

namespace App\Entity;

use App\Repository\BuildRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BuildRepository::class)]
class Build implements EntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $link = null;

    #[ORM\Column]
    private bool $meta = true;

    #[ORM\ManyToOne(inversedBy: 'builds')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Specialization $specialization = null;

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

    final public function getType(): ?string
    {
        return $this->type;
    }

    final public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    final public function getLink(): ?string
    {
        return $this->link;
    }

    final public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    final public function isMeta(): ?bool
    {
        return $this->meta;
    }

    final public function setMeta(bool $meta): self
    {
        $this->meta = $meta;

        return $this;
    }

    final public function getSpecialization(): ?Specialization
    {
        return $this->specialization;
    }

    final public function setSpecialization(?Specialization $specialization): self
    {
        $this->specialization = $specialization;

        return $this;
    }
}
