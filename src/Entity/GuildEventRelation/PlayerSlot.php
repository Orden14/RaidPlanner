<?php

namespace App\Entity\GuildEventRelation;

use App\Entity\Build;
use App\Entity\User;
use App\Repository\PlayerSlotRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerSlotRepository::class)]
class PlayerSlot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'playerSlots')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EventEncounter $eventEncounter = null;

    #[ORM\ManyToOne(inversedBy: 'playerSlots')]
    private ?User $player = null;

    #[ORM\ManyToOne(inversedBy: 'playerSlots')]
    private ?Build $build = null;

    #[ORM\Column]
    private ?bool $tank = false;

    final public function getId(): ?int
    {
        return $this->id;
    }

    final public function getEventEncounter(): ?EventEncounter
    {
        return $this->eventEncounter;
    }

    final public function setEventEncounter(?EventEncounter $eventEncounter): self
    {
        $this->eventEncounter = $eventEncounter;

        return $this;
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

    final public function getBuild(): ?Build
    {
        return $this->build;
    }

    final public function setBuild(?Build $build): self
    {
        $this->build = $build;

        return $this;
    }

    final public function isTank(): ?bool
    {
        return $this->tank;
    }

    final public function setTank(bool $tank): self
    {
        $this->tank = $tank;

        return $this;
    }
}
