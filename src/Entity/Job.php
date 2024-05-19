<?php

namespace App\Entity;

use App\Repository\JobRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobRepository::class)]
class Job
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $color = null;

    #[ORM\Column(length: 255)]
    private ?string $icon = null;

    /** @var Collection<int, Specialization> $specializations */
    #[ORM\OneToMany(targetEntity: Specialization::class, mappedBy: 'job', orphanRemoval: true)]
    private Collection $specializations;

    /**
     * Allows to display default general builds that are not dependent on a specific job/specialization
     */
    #[ORM\Column]
    private bool $isDefault = false;

    public function __construct()
    {
        $this->specializations = new ArrayCollection();
    }

    final public function getId(): ?int
    {
        return $this->id;
    }

    final public function getName(): ?string
    {
        return $this->name;
    }

    final public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    final public function getColor(): ?string
    {
        return $this->color;
    }

    final public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    final public function getIcon(): ?string
    {
        return $this->icon;
    }

    final public function setIcon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return Collection<int, Specialization>
     */
    final public function getSpecializations(): Collection
    {
        return $this->specializations;
    }

    final public function addSpecialization(Specialization $specialization): self
    {
        if (!$this->specializations->contains($specialization)) {
            $this->specializations->add($specialization);
            $specialization->setJob($this);
        }

        return $this;
    }

    final public function removeSpecialization(Specialization $specialization): self
    {
        if ($this->specializations->removeElement($specialization) && $specialization->getJob() === $this) {
            $specialization->setJob(null);
        }

        return $this;
    }

    final public function isDefault(): bool
    {
        return $this->isDefault;
    }

    final public function setIsDefault(bool $isDefault): self
    {
        $this->isDefault = $isDefault;

        return $this;
    }
}
