<?php

namespace App\Entity\GuildEventRelation;

use App\Entity\Build;
use App\Entity\GuildEvent;
use App\Entity\User;
use App\Repository\GuildEventSlotRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GuildEventSlotRepository::class)]
class GuildEventSlot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'guildEventSlots')]
    private ?User $player = null;

    #[ORM\ManyToOne(inversedBy: 'guildEventSlots')]
    private ?GuildEvent $guildEvent = null;

    #[ORM\Column]
    private bool $tank = false;

    #[ORM\ManyToOne]
    private ?Build $build = null;

    final public function getId(): ?int
    {
        return $this->id;
    }

    final public function getPlayer(): ?User
    {
        return $this->player;
    }

    final public function setPlayer(?User $player): self
    {
        $this->player = $player;

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

    final public function isTank(): bool
    {
        return $this->tank;
    }

    final public function setTank(bool $tank = true): self
    {
        $this->tank = $tank;

        return $this;
    }

    final public function getBuild(): ?Build
    {
        return $this->build;
    }

    final public function setBuild(?Build $build): self
    {
        $this->build = $build;

        return $this;
    }
}
