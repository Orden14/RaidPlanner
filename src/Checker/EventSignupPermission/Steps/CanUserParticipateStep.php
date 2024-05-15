<?php

namespace App\Checker\EventSignupPermission\Steps;

use App\Checker\EventParticipationPermission\EventParticipationPermissionChecker;
use App\Entity\GuildEvent;
use App\Interface\EventAttendancePermissionStepInterface;

final readonly class CanUserParticipateStep implements EventAttendancePermissionStepInterface
{
    public function __construct(
        private EventParticipationPermissionChecker $eventParticipationPermissionChecker
    ) {}

    public function check(GuildEvent $guildEvent): bool
    {
        return $this->eventParticipationPermissionChecker->checkIfUserIsAllowedInEvent($guildEvent);
    }
}
