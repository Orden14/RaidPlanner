<?php

namespace App\Entity\Trait;

use App\Entity\CombatLog;
use App\Entity\GuildEventRelation\EventEncounter;
use App\Entity\GuildEventRelation\EventAttendance;
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
     * @var ?Collection<int, EventAttendance>
     */
    #[ORM\OneToMany(targetEntity: EventAttendance::class, mappedBy: 'guildEvent', orphanRemoval: true)]
    private ?Collection $eventAttendances;

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
     * @return Collection<int, EventAttendance>
     */
    final public function getEventAttendances(): Collection
    {
        return $this->eventAttendances;
    }

    final public function addEventAttendance(EventAttendance $eventAttendance): self
    {
        if (!$this->eventAttendances->contains($eventAttendance)) {
            $this->eventAttendances->add($eventAttendance);
            $eventAttendance->setGuildEvent($this);
        }

        return $this;
    }

    final public function removeEventAttendance(EventAttendance $eventAttendance): self
    {
        if ($this->eventAttendances->removeElement($eventAttendance) && $eventAttendance->getGuildEvent() === $this) {
            $eventAttendance->setGuildEvent(null);
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
