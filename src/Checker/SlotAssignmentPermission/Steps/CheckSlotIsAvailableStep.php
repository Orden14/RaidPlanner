<?php

namespace App\Checker\SlotAssignmentPermission\Steps;

use App\Entity\GuildEventRelation\PlayerSlot;
use App\Interface\SlotAssignmentPermissionStepInterface;

final readonly class CheckSlotIsAvailableStep implements SlotAssignmentPermissionStepInterface
{
    public function check(PlayerSlot $playerSlot): bool
    {
        return $playerSlot->getPlayer() === null;
    }
}
