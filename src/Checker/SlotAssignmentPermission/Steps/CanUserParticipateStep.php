<?php

namespace App\Checker\SlotAssignmentPermission\Steps;

use App\Checker\EventParticipationPermission\EventParticipationPermissionChecker;
use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\EventBattle;
use App\Interface\SlotAssignmentPermissionStepInterface;
use Override;

final readonly class CanUserParticipateStep implements SlotAssignmentPermissionStepInterface
{
    public function __construct(
        private EventParticipationPermissionChecker $eventParticipationPermissionChecker,
    ) {}

    #[Override]
    public function check(EventBattle $eventBattle): bool
    {
        /** @var GuildEvent $guildEvent */
        $guildEvent = $eventBattle->getGuildEvent();

        return $this->eventParticipationPermissionChecker->checkIfUserIsAllowedInEvent($guildEvent);
    }
}
