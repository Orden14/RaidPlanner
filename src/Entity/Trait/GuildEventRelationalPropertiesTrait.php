<?php

namespace App\Entity\Trait;

use App\Entity\CombatLog;
use App\Entity\GuildEventRelation\EventSlot;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

trait GuildEventRelationalPropertiesTrait
{
    /**
     * @var ?Collection<int, EventSlot>
     */
    #[ORM\OneToMany(targetEntity: EventSlot::class, mappedBy: 'guildEvent', orphanRemoval: true)]
    private ?Collection $eventSlots;

    /**
     * @var ?Collection<int, CombatLog>
     */
    #[ORM\OneToMany(targetEntity: CombatLog::class, mappedBy: 'guildEvent')]
    private ?Collection $combatLogs;

    /**
     * @return Collection<int, EventSlot>
     */
    final public function getEventSlots(): Collection
    {
        return $this->eventSlots;
    }

    final public function addEventSlot(EventSlot $eventSlot): self
    {
        if (!$this->eventSlots->contains($eventSlot)) {
            $this->eventSlots->add($eventSlot);
            $eventSlot->setGuildEvent($this);
        }

        return $this;
    }

    final public function removeEventSlot(EventSlot $eventSlot): self
    {
        if ($this->eventSlots->removeElement($eventSlot) && $eventSlot->getGuildEvent() === $this) {
            $eventSlot->setGuildEvent(null);
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
