<?php

namespace App\Entity\Trait;

use App\Entity\BuildCategory;
use App\Entity\BuildMessage;
use App\Entity\Specialization;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

trait BuildRelationalPropertiesTrait
{
    #[ORM\ManyToOne(inversedBy: 'builds')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Specialization $specialization = null;

    /** @var Collection<int, BuildCategory> $categories  */
    #[ORM\ManyToMany(targetEntity: BuildCategory::class, inversedBy: 'builds')]
    private Collection $categories;

    /** @var Collection<int, BuildMessage> $buildMessages  */
    #[ORM\OneToMany(targetEntity: BuildMessage::class, mappedBy: 'build', orphanRemoval: true)]
    private Collection $buildMessages;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->buildMessages = new ArrayCollection();
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

    /**
     * @return Collection<int, BuildMessage>
     */
    final public function getBuildMessages(): Collection
    {
        return $this->buildMessages;
    }

    final public function addBuildMessage(BuildMessage $buildMessage): self
    {
        if (!$this->buildMessages->contains($buildMessage)) {
            $this->buildMessages->add($buildMessage);
            $buildMessage->setBuild($this);
        }

        return $this;
    }

    final public function removeBuildMessage(BuildMessage $buildMessage): self
    {
        if ($this->buildMessages->removeElement($buildMessage) && $buildMessage->getBuild() === $this) {
            $buildMessage->setBuild(null);
        }

        return $this;
    }

    final public function getAuthor(): ?User
    {
        return $this->author;
    }

    final public function setAuthor(?User $user): self
    {
        $this->author = $user;

        return $this;
    }
}
