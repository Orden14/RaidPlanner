<?php

namespace App\Interface;

use App\Entity\GuildEventRelation\PlayerSlot;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag]
interface SlotManagementPermissionStepInterface
{
    public function check(PlayerSlot $playerSlot): bool;
}
