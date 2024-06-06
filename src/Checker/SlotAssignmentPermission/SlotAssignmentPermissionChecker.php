<?php

namespace App\Checker\SlotAssignmentPermission;

use App\Entity\GuildEventRelation\PlayerSlot;
use App\Interface\SlotAssignmentPermissionStepInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

final readonly class SlotAssignmentPermissionChecker
{
    public function __construct(
        /** @var SlotAssignmentPermissionStepInterface[] $slotAssignmentPermissionStepInterfaces */
        #[TaggedIterator(SlotAssignmentPermissionStepInterface::class)]
        private iterable $slotAssignmentPermissionStepInterfaces
    ) {}

    public function checkIfUserCanTakeSlot(PlayerSlot $playerSlot): bool
    {
        foreach ($this->slotAssignmentPermissionStepInterfaces as $slotAssignementPermissionStepInterface) {
            if (!$slotAssignementPermissionStepInterface->check($playerSlot)) {
                return false;
            }
        }

        return true;
    }
}
