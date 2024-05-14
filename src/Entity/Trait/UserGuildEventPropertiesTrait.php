<?php

namespace App\Entity\Trait;

use App\Entity\GuildEventRelation\NonActiveSlot;
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
     * @var ?Collection<int, NonActiveSlot>
     */
    #[ORM\OneToMany(targetEntity: NonActiveSlot::class, mappedBy: 'user', orphanRemoval: true)]
    private ?Collection $nonActiveSlots;

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
     * @return Collection<int, NonActiveSlot>
     */
    final public function getNonActiveSlots(): Collection
    {
        return $this->nonActiveSlots;
    }

    final public function addNonActiveSlot(NonActiveSlot $nonActiveSlot): self
    {
        if (!$this->nonActiveSlots->contains($nonActiveSlot)) {
            $this->nonActiveSlots->add($nonActiveSlot);
            $nonActiveSlot->setUser($this);
        }

        return $this;
    }

    final public function removeNonActiveSlot(NonActiveSlot $nonActiveSlot): self
    {
        if ($this->nonActiveSlots->removeElement($nonActiveSlot) && $nonActiveSlot->getUser() === $this) {
            $nonActiveSlot->setUser(null);
        }

        return $this;
    }
}
