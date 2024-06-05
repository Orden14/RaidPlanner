<?php

namespace App\Checker\EventManagementPermission\Steps;

use App\Checker\EventParticipationPermission\EventParticipationPermissionChecker;
use App\Entity\GuildEvent;
use App\Interface\EventManagementPermissionStepInterface;
use Override;

final readonly class CanUserParticipateStep implements EventManagementPermissionStepInterface
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
