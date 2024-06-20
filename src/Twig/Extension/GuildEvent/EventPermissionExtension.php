<?php

namespace App\Twig\Extension\GuildEvent;

use App\Checker\EventManagementPermission\EventManagementPermissionChecker;
use App\Checker\EventSignupPermission\EventSignupPermissionChecker;
use App\Checker\SlotAssignmentPermission\SlotAssignmentPermissionChecker;
use App\Checker\SlotManagementPermission\SlotManagementPermissionChecker;
use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\PlayerSlot;
use Doctrine\ORM\EntityNotFoundException;
use Override;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class EventPermissionExtension extends AbstractExtension
{
    public function __construct(
        private readonly EventSignupPermissionChecker $eventSignupPermissionChecker,
        private readonly SlotAssignmentPermissionChecker $slotAssignmentPermissionChecker,
        private readonly SlotManagementPermissionChecker $slotManagementPermissionChecker,
        private readonly EventManagementPermissionChecker $eventManagementPermissionChecker,
    ) {
    }

    /**
     * @return TwigFunction[]
     */
    #[Override]
    public function getFunctions(): array
    {
        return [
            new TwigFunction('can_user_manage_event', $this->canUserManageEvent(...)),
            new TwigFunction('can_user_take_slot', $this->canUserTakeSlot(...)),
            new TwigFunction('can_user_manage_slot', $this->canUserManageSlot(...)),
            new TwigFunction('can_user_signup', $this->canUserSignup(...)),
        ];
    }

    public function canUserManageEvent(GuildEvent $guildEvent): bool
    {
        return $this->eventManagementPermissionChecker->checkIfUserCanManageEvent($guildEvent);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function canUserTakeSlot(PlayerSlot $playerSlot): bool
    {
        return $this->slotAssignmentPermissionChecker->checkIfUserCanTakeSlot($playerSlot);
    }

    public function canUserManageSlot(PlayerSlot $playerSlot): bool
    {
        return $this->slotManagementPermissionChecker->checkIfUserCanManageSlot($playerSlot);
    }

    public function canUserSignup(GuildEvent $guildEvent): bool
    {
        return $this->eventSignupPermissionChecker->checkIfUserCanSignup($guildEvent);
    }
}
