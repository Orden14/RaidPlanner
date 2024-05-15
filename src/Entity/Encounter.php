<?php

namespace App\Entity;

use App\Entity\GuildEventRelation\EventBattle;
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
     * @var Collection<int, EventBattle>
     */
    #[ORM\OneToMany(targetEntity: EventBattle::class, mappedBy: 'encounter', orphanRemoval: true)]
    private Collection $eventBattles;

    public function __construct()
    {
        $this->eventBattles = new ArrayCollection();
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
     * @return Collection<int, EventBattle>
     */
    final public function getEventBattles(): Collection
    {
        return $this->eventBattles;
    }

    final public function addEventBattle(EventBattle $eventBattle): self
    {
        if (!$this->eventBattles->contains($eventBattle)) {
            $this->eventBattles->add($eventBattle);
            $eventBattle->setEncounter($this);
        }

        return $this;
    }

    final public function removeEventBattle(EventBattle $eventBattle): self
    {
        if ($this->eventBattles->removeElement($eventBattle) && $eventBattle->getEncounter() === $this) {
            $eventBattle->setEncounter(null);
        }

        return $this;
    }
}
