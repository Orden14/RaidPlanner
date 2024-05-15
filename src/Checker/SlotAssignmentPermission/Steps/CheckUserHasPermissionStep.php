<?php

namespace App\Checker\SlotAssignmentPermission\Steps;

use App\Checker\EventParticipationPermission\EventParticipationChecker;
use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\EventBattle;
use App\Interface\SlotAssignmentPermissionStepInterface;

final readonly class CheckUserHasPermissionStep implements SlotAssignmentPermissionStepInterface
{
    public function __construct(
        private EventParticipationChecker $eventParticipationChecker,
    ) {}

    public function check(EventBattle $eventBattle): bool
    {
        /** @var GuildEvent $guildEvent */
        $guildEvent = $eventBattle->getGuildEvent();

        return $this->eventParticipationChecker->checkIfUserIsAllowedInEvent($guildEvent);
    }
}
