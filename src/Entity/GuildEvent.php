<?php

namespace App\Entity;

use App\Entity\Trait\GuildEventPlayersPropertiesTrait;
use App\Enum\GuildEventTypeEnum;
use App\Repository\GuildEventRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GuildEventRepository::class)]
class GuildEvent
{
    use GuildEventPlayersPropertiesTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $start = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $end = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private string $color = '#4c64a8';

    public function __construct()
    {
        $this->guildEventSlots = new ArrayCollection();
    }

    final public function getId(): ?int
    {
        return $this->id;
    }

    final public function getTitle(): ?string
    {
        return $this->title;
    }

    final public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    final public function getStart(): ?DateTimeInterface
    {
        return $this->start;
    }

    final public function setStart(DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    final public function getEnd(): ?DateTimeInterface
    {
        return $this->end;
    }

    final public function setEnd(DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    final public function getType(): GuildEventTypeEnum
    {
        return GuildEventTypeEnum::getEventTypeFromValue($this->type);
    }

    final public function setType(GuildEventTypeEnum $type): self
    {
        $this->type = $type->value;

        return $this;
    }

    final public function getColor(): string
    {
        return $this->color;
    }

    final public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }
}
