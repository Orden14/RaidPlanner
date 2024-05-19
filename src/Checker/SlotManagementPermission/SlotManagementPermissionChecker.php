<?php

namespace App\Checker\SlotManagementPermission;

use App\Entity\GuildEventRelation\PlayerSlot;
use App\Interface\SlotManagementPermissionStepInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

final readonly class SlotManagementPermissionChecker
{
    public function __construct(
        /** @var SlotManagementPermissionStepInterface[] $slotManagementPermissionStepInterfaces */
        #[TaggedIterator(SlotManagementPermissionStepInterface::class)]
        private iterable $slotManagementPermissionStepInterfaces
    ) {}

    public function checkIfUserCanManageSlot(PlayerSlot $playerSlot): bool
    {
        foreach ($this->slotManagementPermissionStepInterfaces as $slotManagementPermissionStepInterface) {
            if (!$slotManagementPermissionStepInterface->check($playerSlot)) {
                return false;
            }
        }

        return true;
    }
}
