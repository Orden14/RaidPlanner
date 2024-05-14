<?php

namespace App\Entity\GuildEventRelation;

use App\Entity\Encounter;
use App\Entity\GuildEvent;
use App\Repository\EventEncounterRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventEncounterRepository::class)]
class EventEncounter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'eventEncounters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GuildEvent $guildEvent = null;

    #[ORM\ManyToOne(inversedBy: 'eventEncounters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Encounter $encounter = null;

    /**
     * @var Collection<int, PlayerSlot>
     */
    #[ORM\OneToMany(targetEntity: PlayerSlot::class, mappedBy: 'eventEncounter', orphanRemoval: true)]
    private Collection $playerSlots;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $createdAt;


    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->playerSlots = new ArrayCollection();
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

    final public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, PlayerSlot>
     */
    final public function getPlayerSlots(): Collection
    {
        return $this->playerSlots;
    }

    final public function addPlayerSlot(PlayerSlot $playerSlot): self
    {
        if (!$this->playerSlots->contains($playerSlot)) {
            $this->playerSlots->add($playerSlot);
            $playerSlot->setEventEncounter($this);
        }

        return $this;
    }

    final public function removePlayerSlot(PlayerSlot $playerSlot): self
    {
        if ($this->playerSlots->removeElement($playerSlot) && $playerSlot->getEventEncounter() === $this) {
            $playerSlot->setEventEncounter(null);
        }

        return $this;
    }

    final public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    final public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
