<?php

namespace App\Checker\SlotAssignmentPermission\Steps;

use App\Checker\EventParticipationPermission\EventParticipationPermissionChecker;
use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\EventBattle;
use App\Entity\GuildEventRelation\PlayerSlot;
use App\Interface\SlotAssignmentPermissionStepInterface;

final readonly class CanUserParticipateStep implements SlotAssignmentPermissionStepInterface
{
    public function __construct(
        private EventParticipationPermissionChecker $eventParticipationPermissionChecker,
    ) {}

    public function check(PlayerSlot $playerSlot): bool
    {
        /** @var EventBattle $eventBattle */
        $eventBattle = $playerSlot->getEventBattle();

        /** @var GuildEvent $guildEvent */
        $guildEvent = $eventBattle->getGuildEvent();

        return $this->eventParticipationPermissionChecker->checkIfUserIsAllowedInEvent($guildEvent);
    }
}
