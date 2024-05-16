<?php

namespace App\Entity\Trait;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;

trait GuildEventPermissionPropertiesTrait
{
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\Column]
    private bool $oldMembersAllowed = false;

    #[ORM\Column]
    private bool $membersManageEvent = false;

    final public function getOwner(): ?User
    {
        return $this->owner;
    }

    final public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    final public function isOldMembersAllowed(): bool
    {
        return $this->oldMembersAllowed;
    }

    final public function setOldMembersAllowed(bool $oldMembersAllowed): self
    {
        $this->oldMembersAllowed = $oldMembersAllowed;

        return $this;
    }

    final public function canMembersManageEvent(): bool
    {
        return $this->membersManageEvent;
    }

    final public function setMembersManageEvent(bool $membersManageEvent): self
    {
        $this->membersManageEvent = $membersManageEvent;

        return $this;
    }
}
