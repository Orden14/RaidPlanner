<?php

namespace App\Entity\GuildEventRelation;

use App\Entity\Build;
use App\Entity\GuildEvent;
use App\Entity\User;
use App\Enum\GuildEventSlotTypeEnum;
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

    #[ORM\ManyToOne(inversedBy: 'guildEventSlots')]
    private ?GuildEventEncounter $guildEventEncounter = null;

    #[ORM\Column(length: 255)]
    private ?string $slotType = GuildEventSlotTypeEnum::PLAYER->value;

    #[ORM\ManyToOne]
    private ?Build $build = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column]
    private bool $tank = false;

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

    final public function getGuildEventEncounter(): ?GuildEventEncounter
    {
        return $this->guildEventEncounter;
    }

    final public function setGuildEventEncounter(?GuildEventEncounter $guildEventEncounter): self
    {
        $this->guildEventEncounter = $guildEventEncounter;

        return $this;
    }

    final public function getSlotType(): GuildEventSlotTypeEnum
    {
        return GuildEventSlotTypeEnum::getSlotTypeByValue($this->slotType);
    }

    final public function setSlotType(GuildEventSlotTypeEnum $slotType): self
    {
        $this->slotType = $slotType->value;

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

    final public function getComment(): ?string
    {
        return $this->comment;
    }

    final public function setComment(string $comment): self
    {
        $this->comment = $comment;

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
}
