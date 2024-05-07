<?php

namespace App\Entity;

use App\Repository\InstanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InstanceRepository::class)]
class Instance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $tag = null;

    /**
     * @var Collection<int, Encounter>
     */
    #[ORM\OneToMany(targetEntity: Encounter::class, mappedBy: 'instance', orphanRemoval: true)]
    private Collection $encounters;

    public function __construct()
    {
        $this->encounters = new ArrayCollection();
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

    final public function getTag(): ?string
    {
        return $this->tag;
    }

    final public function setTag(string $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * @return Collection<int, Encounter>
     */
    final public function getEncounters(): Collection
    {
        return $this->encounters;
    }

    final public function addEncounter(Encounter $encounter): static
    {
        if (!$this->encounters->contains($encounter)) {
            $this->encounters->add($encounter);
            $encounter->setInstance($this);
        }

        return $this;
    }

    final public function removeEncounter(Encounter $encounter): self
    {
        if ($this->encounters->removeElement($encounter) && $encounter->getInstance() === $this) {
            $encounter->setInstance(null);
        }

        return $this;
    }
}
