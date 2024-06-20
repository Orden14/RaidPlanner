<?php

namespace App\Entity;

use App\Repository\JobRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: JobRepository::class)]
class Job
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotNull(message: 'Le nom de la classe ne peut pas être vide.')]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Assert\NotNull(message: 'La couleur de la classe ne peut pas être vide.')]
    #[Assert\CssColor(message: 'La couleur doit être un code couleur valide.')]
    #[ORM\Column(length: 255)]
    private ?string $color = null;

    #[ORM\Column(length: 255)]
    private ?string $icon = null;

    /** @var Collection<int, Specialization> $specializations */
    #[ORM\OneToMany(targetEntity: Specialization::class, mappedBy: 'job', orphanRemoval: true)]
    private Collection $specializations;

    /**
     * Allows to display default general builds that do not dependent on a specific job/specialization.
     */
    #[ORM\Column]
    private bool $defaultJob = false;

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

    final public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    final public function getColor(): ?string
    {
        return $this->color;
    }

    final public function setColor(?string $color): self
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

    final public function isDefaultJob(): bool
    {
        return $this->defaultJob;
    }

    final public function setDefaultJob(bool $defaultJob): self
    {
        $this->defaultJob = $defaultJob;

        return $this;
    }
}
