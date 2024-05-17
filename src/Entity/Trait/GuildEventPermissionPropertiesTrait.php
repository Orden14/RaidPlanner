<?php

namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;

trait GuildEventPermissionPropertiesTrait
{
    #[ORM\Column]
    private bool $oldMembersAllowed = false;

    #[ORM\Column]
    private bool $membersManageEvent = false;

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
