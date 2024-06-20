<?php

namespace App\Checker\SlotManagementPermission\Step;

use App\Checker\EventManagementPermission\EventManagementPermissionChecker;
use App\Entity\GuildEventRelation\PlayerSlot;
use App\Entity\User;
use App\Interface\SlotManagementPermissionStepInterface;
use Override;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class CanUserManageSlotStep implements SlotManagementPermissionStepInterface
{
    public function __construct(
        private Security $security,
        private EventManagementPermissionChecker $eventManagementPermissionChecker,
    ) {
    }

    #[Override]
    public function check(PlayerSlot $playerSlot): bool
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        return $this->eventManagementPermissionChecker->checkIfUserCanManageEvent($playerSlot->getEventBattle()?->getGuildEvent())
            || $currentUser === $playerSlot->getPlayer();
    }
}
