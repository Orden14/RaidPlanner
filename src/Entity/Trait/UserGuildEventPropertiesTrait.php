<?php

namespace App\Entity\Trait;

use App\Entity\GuildEventRelation\NonPlayerSlot;
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
     * @var ?Collection<int, NonPlayerSlot>
     */
    #[ORM\OneToMany(targetEntity: NonPlayerSlot::class, mappedBy: 'user', orphanRemoval: true)]
    private ?Collection $nonPlayerSlots;

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
            $nonPlayerSlot->setUser($this);
        }

        return $this;
    }

    final public function removeNonPlayerSlot(NonPlayerSlot $nonPlayerSlot): self
    {
        if ($this->nonPlayerSlots->removeElement($nonPlayerSlot) && $nonPlayerSlot->getUser() === $this) {
            $nonPlayerSlot->setUser(null);
        }

        return $this;
    }
}
