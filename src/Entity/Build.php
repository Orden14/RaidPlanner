<?php

namespace App\Entity;

use App\Repository\BuildRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BuildRepository::class)]
class Build
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;
    #[ORM\Column(length: 255)]
    private ?string $link = null;

    #[ORM\Column]
    private bool $meta = true;

    #[ORM\ManyToOne(inversedBy: 'builds')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Specialization $specialization = null;

    /** @var Collection<int, BuildCategory> $categories  */
    #[ORM\ManyToMany(targetEntity: BuildCategory::class, inversedBy: 'builds')]
    private Collection $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
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

    final public function getLink(): ?string
    {
        return $this->link;
    }

    final public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    final public function isMeta(): ?bool
    {
        return $this->meta;
    }

    final public function setMeta(bool $meta): self
    {
        $this->meta = $meta;

        return $this;
    }

    final public function getSpecialization(): ?Specialization
    {
        return $this->specialization;
    }

    final public function setSpecialization(?Specialization $specialization): self
    {
        $this->specialization = $specialization;

        return $this;
    }

    /**
     * @return Collection<int, BuildCategory>
     */
    final public function getCategories(): Collection
    {
        return $this->categories;
    }

    /**
     * @param Collection<int, BuildCategory> $categories
     */
    final public function setCategories(Collection $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    final public function addCategory(BuildCategory $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    final public function removeCategory(BuildCategory $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    final public function isDefault(): bool
    {
        return $this->specialization->getJob()?->isDefault();
    }
}
