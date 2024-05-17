<?php

namespace App\Entity\GuildEventRelation;

use App\Entity\GuildEvent;
use App\Entity\User;
use App\Enum\AttendanceTypeEnum;
use App\Repository\EventAttendanceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventAttendanceRepository::class)]
class EventAttendance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'eventAttendances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'eventAttendances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GuildEvent $guildEvent = null;

    #[ORM\Column(length: 255)]
    private ?string $type = AttendanceTypeEnum::BACKUP->value;

    #[ORM\Column]
    private bool $eventOwner = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment = null;

    final public function getId(): ?int
    {
        return $this->id;
    }

    final public function getUser(): ?User
    {
        return $this->user;
    }

    final public function setUser(?User $user): self
    {
        $this->user = $user;

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

    final public function getType(): ?AttendanceTypeEnum
    {
        return AttendanceTypeEnum::from($this->type);
    }

    final public function setType(AttendanceTypeEnum $type): self
    {
        $this->type = $type->value;

        return $this;
    }

    final public function getComment(): ?string
    {
        return $this->comment;
    }

    final public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    final public function setEventOwner(bool $eventOwner): self
    {
        $this->eventOwner = $eventOwner;

        return $this;

    }

    final public function isEventOwner(): bool
    {
        return $this->eventOwner;
    }
}
