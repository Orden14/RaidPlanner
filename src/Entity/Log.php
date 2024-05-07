<?php

namespace App\Entity;

use App\Enum\LogTypeEnum;
use App\Repository\LogRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LogRepository::class)]
class Log
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTime $date;

    #[ORM\ManyToOne]
    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $type = null;

    #[ORM\Column(nullable: true)]
    private ?int $targetId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $targetText = null;

    public function __construct()
    {
        $this->date = new DateTime();
    }

    final public function getId(): ?int
    {
        return $this->id;
    }

    final public function getDate(): DateTime
    {
        return $this->date;
    }

    final public function setDate(DateTime $date): self
    {
        $this->date = $date;

        return $this;
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

    final public function getType(): ?LogTypeEnum
    {
        return LogTypeEnum::getTypeFromValue($this->type);
    }

    final public function setType(LogTypeEnum $type): self
    {
        $this->type = $type->name;

        return $this;
    }

    final public function getTargetId(): ?int
    {
        return $this->targetId;
    }

    final public function setTargetId(?int $targetId): self
    {
        $this->targetId = $targetId;

        return $this;
    }

    final public function getTargetText(): ?string
    {
        return $this->targetText;
    }

    final public function setTargetText(?string $targetText): static
    {
        $this->targetText = $targetText;

        return $this;
    }
}
