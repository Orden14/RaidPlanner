<?php

namespace App\Entity\GuildEventRelation;

use App\Entity\Encounter;
use App\Entity\GuildEvent;
use App\Repository\GuildEventEncounterRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GuildEventEncounterRepository::class)]
class GuildEventEncounter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?Encounter $encounter = null;

    #[ORM\ManyToOne(inversedBy: 'guildEventEncounters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GuildEvent $guildEvent = null;

    /**
     * @var Collection<int, GuildEventSlot>
     */
    #[ORM\OneToMany(targetEntity: GuildEventSlot::class, mappedBy: 'guildEventEncounter', orphanRemoval: true)]
    private Collection $guildEventSlots;

    public function __construct()
    {
        $this->guildEventSlots = new ArrayCollection();
    }

    final public function getId(): ?int
    {
        return $this->id;
    }

    final public function getEncounter(): ?Encounter
    {
        return $this->encounter;
    }

    final public function setEncounter(?Encounter $encounter): self
    {
        $this->encounter = $encounter;

        return $this;
    }

    final public function getGuildEvent(): ?GuildEvent
    {
        return $this->guildEvent;
    }

    final public function setGuildEvent(?GuildEvent $guildEvent): self
    {
        $this->guildEvent = $guildEvent;

        return $this;
    }

    /**
     * @return Collection<int, GuildEventSlot>
     */
    final public function getGuildEventSlots(): Collection
    {
        return $this->guildEventSlots;
    }

    final public function addGuildEventSlot(GuildEventSlot $guildEventSlot): self
    {
        if (!$this->guildEventSlots->contains($guildEventSlot)) {
            $this->guildEventSlots->add($guildEventSlot);
            $guildEventSlot->setGuildEventEncounter($this);
        }

        return $this;
    }

    final public function removeGuildEventSlot(GuildEventSlot $guildEventSlot): self
    {
        if ($this->guildEventSlots->removeElement($guildEventSlot) && $guildEventSlot->getGuildEventEncounter() === $this) {
            $guildEventSlot->setGuildEventEncounter(null);
        }

        return $this;
    }
}
