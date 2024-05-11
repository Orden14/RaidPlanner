<?php

namespace App\Entity\Trait;

use App\Entity\CombatLog;
use App\Entity\GuildEventRelation\GuildEventEncounter;
use App\Entity\GuildEventRelation\GuildEventSlot;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

trait GuildEventRelationalPropertiesTrait
{
    /**
     * @var ?Collection<int, GuildEventEncounter>
     */
    #[ORM\OneToMany(targetEntity: GuildEventEncounter::class, mappedBy: 'guildEvent', orphanRemoval: true)]
    private ?Collection $guildEventEncounters;

    /**
     * @var ?Collection<int, GuildEventSlot>
     */
    #[ORM\OneToMany(targetEntity: GuildEventSlot::class, mappedBy: 'guildEvent', orphanRemoval: true)]
    private ?Collection $guildEventSlots;

    /**
     * @var ?Collection<int, CombatLog>
     */
    #[ORM\OneToMany(targetEntity: CombatLog::class, mappedBy: 'guildEvent')]
    private ?Collection $combatLogs;

    /**
     * @return Collection<int, GuildEventEncounter>
     */
    final public function getGuildEventEncounters(): Collection
    {
        return $this->guildEventEncounters;
    }

    final public function addGuildEventEncounter(GuildEventEncounter $guildEventEncounter): self
    {
        if (!$this->guildEventEncounters->contains($guildEventEncounter)) {
            $this->guildEventEncounters->add($guildEventEncounter);
            $guildEventEncounter->setGuildEvent($this);
        }

        return $this;
    }

    final public function removeGuildEventEncounter(GuildEventEncounter $guildEventEncounter): self
    {
        if ($this->guildEventEncounters->removeElement($guildEventEncounter) && $guildEventEncounter->getGuildEvent() === $this) {
            $guildEventEncounter->setGuildEvent(null);
        }

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
            $guildEventSlot->setGuildEvent($this);
        }

        return $this;
    }

    final public function removeGuildEventSlot(GuildEventSlot $guildEventSlot): self
    {
        if ($this->guildEventSlots->removeElement($guildEventSlot) && $guildEventSlot->getGuildEvent() === $this) {
            $guildEventSlot->setGuildEvent(null);
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
