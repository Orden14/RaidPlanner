<?php

namespace App\Entity;

use App\Repository\BuildCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BuildCategoryRepository::class)]
class BuildCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotNull(message: 'Le nom de la catégorie est obligatoire.')]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $icon = null;

    /**
     * @var Collection<int, Build> $builds
     */
    #[ORM\ManyToMany(targetEntity: Build::class, mappedBy: 'categories')]
    private Collection $builds;

    public function __construct()
    {
        $this->builds = new ArrayCollection();
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

    final public function getIcon(): ?string
    {
        return $this->icon;
    }

    final public function setIcon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    final public function addBuild(Build $build): self
    {
        if (!$this->builds->contains($build)) {
            $this->builds->add($build);
            $build->addCategory($this);
        }

        return $this;
    }

    final public function removeBuild(Build $build): self
    {
        if ($this->builds->removeElement($build) && $build->getCategories()->contains($this)) {
            $build->removeCategory($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Build>
     */
    final public function getBuilds(): Collection
    {
        return $this->builds;
    }
}
