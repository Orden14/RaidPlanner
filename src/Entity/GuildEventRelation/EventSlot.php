<?php

namespace App\Entity\GuildEventRelation;

use App\Entity\Build;
use App\Entity\Encounter;
use App\Entity\GuildEvent;
use App\Entity\User;
use App\Enum\EventSlotTypeEnum;
use App\Repository\EventSlotRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventSlotRepository::class)]
class EventSlot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'eventSlots')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GuildEvent $guildEvent = null;

    #[ORM\ManyToOne(inversedBy: 'guildEventSlots')]
    private ?User $player = null;

    #[ORM\Column(length: 255)]
    private ?string $slotType = EventSlotTypeEnum::PLAYER->value;

    #[ORM\ManyToOne]
    private ?Build $build = null;

    #[ORM\ManyToOne]
    private ?Encounter $encounter = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column]
    private bool $tank = false;

    final public function getId(): ?int
    {
        return $this->id;
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

    final public function getPlayer(): ?User
    {
        return $this->player;
    }

    final public function setPlayer(?User $player): self
    {
        $this->player = $player;

        return $this;
    }

    final public function getSlotType(): EventSlotTypeEnum
    {
        return EventSlotTypeEnum::getSlotTypeByValue($this->slotType);
    }

    final public function setSlotType(EventSlotTypeEnum $slotType): self
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

    final public function getEncounter(): ?Encounter
    {
        return $this->encounter;
    }

    final public function setEncounter(?Encounter $encounter): self
    {
        $this->encounter = $encounter;

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
