<?php

namespace App\Entity\Trait;

use App\Entity\CombatLog;
use App\Entity\GuildEventRelation\EventEncounter;
use App\Entity\GuildEventRelation\NonPlayerSlot;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

trait GuildEventRelationalPropertiesTrait
{
    /**
     * @var ?Collection<int, EventEncounter>
     */
    #[ORM\OneToMany(targetEntity: EventEncounter::class, mappedBy: 'guildEvent', orphanRemoval: true)]
    private ?Collection $eventEncounters;

    /**
     * @var ?Collection<int, NonPlayerSlot>
     */
    #[ORM\OneToMany(targetEntity: NonPlayerSlot::class, mappedBy: 'guildEvent', orphanRemoval: true)]
    private ?Collection $nonPlayerSlots;

    /**
     * @var ?Collection<int, CombatLog>
     */
    #[ORM\OneToMany(targetEntity: CombatLog::class, mappedBy: 'guildEvent')]
    private ?Collection $combatLogs;

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
            $eventEncounter->setGuildEvent($this);
        }

        return $this;
    }

    final public function removeEventEncounter(EventEncounter $eventEncounter): self
    {
        if ($this->eventEncounters->removeElement($eventEncounter) && $eventEncounter->getGuildEvent() === $this) {
            $eventEncounter->setGuildEvent(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, NonPlayerSlot>
     */
    final public function getNonPlayerSlots(): Collection
    {
        return $this->nonPlayerSlots;
    }

    final public function addNonPlayerSlot(NonPlayerSlot $nonPlayerSlot): self
    {
        if (!$this->nonPlayerSlots->contains($nonPlayerSlot)) {
            $this->nonPlayerSlots->add($nonPlayerSlot);
            $nonPlayerSlot->setGuildEvent($this);
        }

        return $this;
    }

    final public function removeNonPlayerSlot(NonPlayerSlot $nonPlayerSlot): self
    {
        if ($this->nonPlayerSlots->removeElement($nonPlayerSlot) && $nonPlayerSlot->getGuildEvent() === $this) {
            $nonPlayerSlot->setGuildEvent(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, CombatLog>
     */
    final public function getCombatLogs(): Collection
    {
        return $this->combatLogs;
    }

    final public function addCombatLog(CombatLog $combatLog): self
    {
        if (!$this->combatLogs->contains($combatLog)) {
            $this->combatLogs->add($combatLog);
            $combatLog->setGuildEvent($this);
        }

        return $this;
    }

    final public function removeCombatLog(CombatLog $combatLog): self
    {
        if ($this->combatLogs->removeElement($combatLog) && $combatLog->getGuildEvent() === $this) {
            $combatLog->setGuildEvent(null);
        }

        return $this;
    }
}
