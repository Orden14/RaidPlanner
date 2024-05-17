<?php

namespace App\Checker\EventManagementPermission\Steps;

use App\Entity\GuildEvent;
use App\Enum\RolesEnum;
use App\Interface\EventManagementPermissionStepInterface;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class CheckMemberCanManageStep implements EventManagementPermissionStepInterface
{
    public function __construct(
        private Security $security
    ) {}

    public function check(GuildEvent $guildEvent): bool
    {
        return $guildEvent->canMembersManageEvent() && $this->security->isGranted(RolesEnum::MEMBER->value);
    }
}
