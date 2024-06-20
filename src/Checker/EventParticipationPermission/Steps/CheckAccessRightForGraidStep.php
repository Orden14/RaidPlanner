<?php

namespace App\Checker\EventParticipationPermission\Steps;

use App\Entity\GuildEvent;
use App\Enum\RolesEnum;
use App\Interface\EventParticipationPermissionStepInterface;
use Override;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class CheckAccessRightForGraidStep implements EventParticipationPermissionStepInterface
{
    public function __construct(
        private Security $security,
    ) {
    }

    #[Override]
    public function check(GuildEvent $guildEvent): bool
    {
        return !$guildEvent->isGuildRaid() || $this->security->isGranted(RolesEnum::TRIAL->value);
    }
}
