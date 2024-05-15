<?php

namespace App\Checker\EventSignupPermission\Steps;

use App\Checker\EventParticipationPermission\EventParticipationChecker;
use App\Entity\GuildEvent;
use App\Interface\EventAttendancePermissionStepInterface;

final readonly class CanUserParticipateStep implements EventAttendancePermissionStepInterface
{
    public function __construct(
        private EventParticipationChecker $eventParticipationChecker
    ) {}

    public function check(GuildEvent $guildEvent): bool
    {
        return $this->eventParticipationChecker->checkIfUserIsAllowedInEvent($guildEvent);
    }
}
