<?php

namespace App\Checker\SlotManagementPermission\Step;

use App\Checker\EventManagementPermission\EventManagementPermissionChecker;
use App\Entity\GuildEventRelation\PlayerSlot;
use App\Entity\User;
use App\Interface\SlotManagementPermissionStepInterface;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class CanUserManageSlotStep implements SlotManagementPermissionStepInterface
{
    public function __construct(
        private Security                         $security,
        private EventManagementPermissionChecker $eventManagementPermissionChecker,
    ) {}

    public function check(PlayerSlot $playerSlot): bool
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        return $this->eventManagementPermissionChecker->checkIfUserCanManageEvent($playerSlot->getEventBattle()?->getGuildEvent())
            || $currentUser === $playerSlot->getPlayer();
    }
}
