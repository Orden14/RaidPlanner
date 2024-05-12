<?php

namespace App\Entity;

use App\Entity\GuildEventRelation\EventEncounter;
use App\Repository\EncounterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @var Collection<int, EventEncounter>
     */
    #[ORM\OneToMany(targetEntity: EventEncounter::class, mappedBy: 'encounter', orphanRemoval: true)]
    private Collection $eventEncounters;

    public function __construct()
    {
        $this->eventEncounters = new ArrayCollection();
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

    final public function getInstance(): ?Instance
    {
        return $this->instance;
    }

    final public function setInstance(?Instance $instance): self
    {
        $this->instance = $instance;

        return $this;
    }

    /**
     * @return Collection<int, EventEncounter>
     */
    final public function getEventEncounters(): Collection
    {
        return $this->eventEncounters;
    }

    final public function addEventEncounter(EventEncounter $eventEncounter): self
    {
        if (!$this->eventEncounters->contains($eventEncounter)) {
            $this->eventEncounters->add($eventEncounter);
            $eventEncounter->setEncounter($this);
        }

        return $this;
    }

    final public function removeEventEncounter(EventEncounter $eventEncounter): self
    {
        if ($this->eventEncounters->removeElement($eventEncounter) && $eventEncounter->getEncounter() === $this) {
            $eventEncounter->setEncounter(null);
        }

        return $this;
    }
}
