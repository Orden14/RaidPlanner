<?php

namespace App\Entity;

use App\Entity\Trait\GuildEventPermissionPropertiesTrait;
use App\Entity\Trait\GuildEventRelationalPropertiesTrait;
use App\Enum\GuildEventStatusEnum;
use App\Enum\InstanceTypeEnum;
use App\Repository\GuildEventRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GuildEventRepository::class)]
class GuildEvent
{
    use GuildEventPermissionPropertiesTrait;
    use GuildEventRelationalPropertiesTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotNull(message: 'Le titre ne peut pas être nul.')]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $start = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $end = null;

    #[Assert\Choice(
        callback: [InstanceTypeEnum::class, 'toArray'],
        message: "Le type d'instance n'est pas valide."
    )]
    #[ORM\Column(length: 255)]
    private string $type = InstanceTypeEnum::RAID->value;

    #[Assert\CssColor(message: 'La couleur n\'est pas valide.')]
    #[ORM\Column(length: 255)]
    private string $color = '#4c64a8';

    #[Assert\Choice(
        callback: [GuildEventStatusEnum::class, 'toArray'],
        message: "Le statut de l'événement n'est pas valide."
    )]
    #[ORM\Column(length: 255)]
    private string $status = GuildEventStatusEnum::OPEN->value;

    #[ORM\Column]
    private bool $guildRaid = true;

    public function __construct()
    {
        $this->eventBattles = new ArrayCollection();
        $this->eventAttendances = new ArrayCollection();
    }

    final public function getId(): ?int
    {
        return $this->id;
    }

    final public function getTitle(): ?string
    {
        return $this->title;
    }

    final public function setTitle(?string $title): self
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

    final public function getType(): InstanceTypeEnum
    {
        return InstanceTypeEnum::from($this->type);
    }

    final public function setType(InstanceTypeEnum $type): self
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

    final public function getStatus(): ?GuildEventStatusEnum
    {
        return GuildEventStatusEnum::from($this->status);
    }

    final public function setStatus(GuildEventStatusEnum $status): self
    {
        $this->status = $status->value;

        return $this;
    }

    final public function isGuildRaid(): bool
    {
        return $this->guildRaid;
    }

    final public function setGuildRaid(bool $guildRaid): self
    {
        $this->guildRaid = $guildRaid;

        return $this;
    }
}
