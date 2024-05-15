<?php

namespace App\Checker\SlotAssignmentPermission;

use App\Entity\GuildEventRelation\EventEncounter;
use App\Interface\SlotAssignmentPermissionStepInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

final readonly class SlotAssignmentChecker
{
    public function __construct(
        /** @var SlotAssignmentPermissionStepInterface[] $slotAssignmentPermissionStepInterfaces */
        #[TaggedIterator(SlotAssignmentPermissionStepInterface::class)]
        private iterable $slotAssignmentPermissionStepInterfaces
    ) {}

    public function checkIfUserCanTakeSlot(EventEncounter $eventEncounter): bool
    {
        foreach ($this->slotAssignmentPermissionStepInterfaces as $slotAssignementPermissionStepInterface) {
            if (!$slotAssignementPermissionStepInterface->check($eventEncounter)) {
                return false;
            }
        }
        return true;
    }
}
