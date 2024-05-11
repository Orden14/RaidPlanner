<?php

namespace App\Entity;

use App\Repository\CombatLogRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CombatLogRepository::class)]
class CombatLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $link = null;

    #[ORM\ManyToOne(inversedBy: 'combatLogs')]
    private ?GuildEvent $guildEvent = null;

    final public function getId(): ?int
    {
        return $this->id;
    }

    final public function getLink(): ?string
    {
        return $this->link;
    }

    final public function setLink(string $link): self
    {
        $this->link = $link;

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
}
