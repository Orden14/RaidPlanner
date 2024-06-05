<?php

namespace App\Checker\EventSignupPermission\Steps;

use App\Checker\EventParticipationPermission\EventParticipationPermissionChecker;
use App\Entity\GuildEvent;
use App\Interface\EventAttendancePermissionStepInterface;
use Override;

final readonly class CanUserParticipateStep implements EventAttendancePermissionStepInterface
{
    public function __construct(
        private EventParticipationPermissionChecker $eventParticipationPermissionChecker
    ) {}

    #[Override]
    public function check(GuildEvent $guildEvent): bool
    {
        return $this->eventParticipationPermissionChecker->checkIfUserIsAllowedInEvent($guildEvent);
    }
}
