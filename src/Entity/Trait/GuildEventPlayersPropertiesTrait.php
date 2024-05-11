<?php

namespace App\Entity\Trait;

use App\Entity\GuildEventRelation\GuildEventSlot;
use App\Enum\GuildEventTypeEnum;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

trait GuildEventPlayersPropertiesTrait
{
    /**
     * @var ?Collection<int, GuildEventSlot>
     */
    #[ORM\OneToMany(targetEntity: GuildEventSlot::class, mappedBy: 'guildEvent')]
    private ?Collection $guildEventSlots;

    #[ORM\Column]
    private ?int $maxPlayers = null;

    #[ORM\Column]
    private bool $oldMembersAllowed = false;

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

    final public function getMaxPlayers(): ?int
    {
        return $this->maxPlayers;
    }

    /**
     * @param int|null $maxPlayers Leave null to get default value by type
     */
    final public function setMaxPlayers(?int $maxPlayers = null): self
    {
        if ($maxPlayers) {
            $this->maxPlayers = $maxPlayers;
        } else {
            $this->maxPlayers = GuildEventTypeEnum::getMaxPlayersByType($this->getType());
        }

        return $this;
    }

    final public function isOldMembersAllowed(): bool
    {
        return $this->oldMembersAllowed;
    }

    final public function setOldMembersAllowed(bool $oldMembersAllowed): self
    {
        $this->oldMembersAllowed = $oldMembersAllowed;

        return $this;
    }
}
