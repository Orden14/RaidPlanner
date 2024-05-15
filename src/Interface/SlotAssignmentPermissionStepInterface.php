<?php

namespace App\Interface;

use App\Entity\GuildEventRelation\EventBattle;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag]
interface SlotAssignmentPermissionStepInterface
{
    public function check(EventBattle $eventBattle): bool;
}
