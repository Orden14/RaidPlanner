<?php

namespace App\Entity\Trait;

use App\Entity\GuildEventRelation\EventAttendance;
use App\Entity\GuildEventRelation\PlayerSlot;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


trait UserGuildEventPropertiesTrait
{
    /**
     * @var ?Collection<int, PlayerSlot>
     */
    #[ORM\OneToMany(targetEntity: PlayerSlot::class, mappedBy: 'player')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Collection $playerSlots;

    /**
     * @var ?Collection<int, EventAttendance>
     */
    #[ORM\OneToMany(targetEntity: EventAttendance::class, mappedBy: 'user', orphanRemoval: true)]
    private ?Collection $eventAttendances;

    /**
     * @return Collection<int, PlayerSlot>
     */
    final public function getPlayerSlots(): Collection
    {
        return $this->playerSlots;
    }

    final public function addPlayerSlot(PlayerSlot $playerSlot): self
    {
        if (!$this->playerSlots->contains($playerSlot)) {
            $this->playerSlots->add($playerSlot);
            $playerSlot->setPlayer($this);
        }

        return $this;
    }

    final public function removePlayerSlot(PlayerSlot $playerSlot): self
    {
        if ($this->playerSlots->removeElement($playerSlot) && $playerSlot->getPlayer() === $this) {
            $playerSlot->setPlayer(null);
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
            $eventAttendance->setUser($this);
        }

        return $this;
    }

    final public function removeEventAttendance(EventAttendance $eventAttendance): self
    {
        if ($this->eventAttendances->removeElement($eventAttendance) && $eventAttendance->getUser() === $this) {
            $eventAttendance->setUser(null);
        }

        return $this;
    }
}
