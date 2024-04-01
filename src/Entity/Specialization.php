<?php

namespace App\Entity;

use App\Repository\SpecializationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpecializationRepository::class)]
class Specialization
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Icon = null;

    #[ORM\ManyToOne(inversedBy: 'specializations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Job $job = null;

    #[ORM\OneToMany(targetEntity: Build::class, mappedBy: 'specialization', orphanRemoval: true)]
    private Collection $builds;

    public function __construct()
    {
        $this->builds = new ArrayCollection();
    }

    public function getId(): ?int
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

    final public function getIcon(): ?string
    {
        return $this->Icon;
    }

    final public function setIcon(?string $Icon): self
    {
        $this->Icon = $Icon;

        return $this;
    }

    final public function getJob(): ?Job
    {
        return $this->job;
    }

    final public function setJob(?Job $job): self
    {
        $this->job = $job;

        return $this;
    }

    /**
     * @return Collection<int, Build>
     */
    public function getBuilds(): Collection
    {
        return $this->builds;
    }

    public function addBuild(Build $build): static
    {
        if (!$this->builds->contains($build)) {
            $this->builds->add($build);
            $build->setSpecialization($this);
        }

        return $this;
    }

    public function removeBuild(Build $build): static
    {
        if ($this->builds->removeElement($build)) {
            // set the owning side to null (unless already changed)
            if ($build->getSpecialization() === $this) {
                $build->setSpecialization(null);
            }
        }

        return $this;
    }
}
