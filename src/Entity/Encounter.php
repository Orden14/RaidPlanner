<?php

namespace App\Entity;

use App\Repository\EncounterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EncounterRepository::class)]
class Encounter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'encounters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Instance $instance = null;

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

    final public function getInstance(): ?Instance
    {
        return $this->instance;
    }

    final public function setInstance(?Instance $instance): self
    {
        $this->instance = $instance;

        return $this;
    }
}
