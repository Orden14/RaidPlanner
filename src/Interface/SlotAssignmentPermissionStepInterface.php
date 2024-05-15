<?php

namespace App\Interface;

use App\Entity\GuildEventRelation\EventEncounter;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag]
interface SlotAssignmentPermissionStepInterface
{
    public function check(EventEncounter $eventEncounter): bool;
}
