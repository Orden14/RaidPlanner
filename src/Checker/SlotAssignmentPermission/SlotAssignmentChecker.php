<?php

namespace App\Checker\SlotAssignmentPermission;

use App\Entity\GuildEventRelation\EventBattle;
use App\Interface\SlotAssignmentPermissionStepInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

final readonly class SlotAssignmentChecker
{
    public function __construct(
        /** @var SlotAssignmentPermissionStepInterface[] $slotAssignmentPermissionStepInterfaces */
        #[TaggedIterator(SlotAssignmentPermissionStepInterface::class)]
        private iterable $slotAssignmentPermissionStepInterfaces
    ) {}

    public function checkIfUserCanTakeSlot(EventBattle $eventBattle): bool
    {
        foreach ($this->slotAssignmentPermissionStepInterfaces as $slotAssignementPermissionStepInterface) {
            if (!$slotAssignementPermissionStepInterface->check($eventBattle)) {
                return false;
            }
        }

        return true;
    }
}
