<?php

namespace App\Entity\Trait;

use App\Entity\GuildEventRelation\EventAttendance;
use App\Entity\GuildEventRelation\EventBattle;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

trait GuildEventRelationalPropertiesTrait
{
    /**
     * @var ?Collection<int, EventBattle>
     */
    #[ORM\OneToMany(targetEntity: EventBattle::class, mappedBy: 'guildEvent', orphanRemoval: true)]
    private ?Collection $eventBattles;

    /**
     * @var ?Collection<int, EventAttendance>
     */
    #[ORM\OneToMany(targetEntity: EventAttendance::class, mappedBy: 'guildEvent', orphanRemoval: true)]
    private ?Collection $eventAttendances;

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
            $eventBattle->setGuildEvent($this);
        }

        return $this;
    }

    final public function removeEventBattle(EventBattle $eventBattle): self
    {
        if ($this->eventBattles->removeElement($eventBattle) && $eventBattle->getGuildEvent() === $this) {
            $eventBattle->setGuildEvent(null);
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
}
