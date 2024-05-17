<?php

namespace App\Checker\EventManagementPermission\Steps;

use App\Entity\GuildEvent;
use App\Enum\RolesEnum;
use App\Interface\EventManagementPermissionStepInterface;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class CheckManagementPermissionForGraidStep implements EventManagementPermissionStepInterface
{
    public function __construct(
        private Security $security,
    ) {}

    public function check(GuildEvent $guildEvent): bool
    {
        return !$guildEvent->isGuildRaid() || $this->security->isGranted(RolesEnum::ADMIN->value);
    }
}
