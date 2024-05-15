<?php

namespace App\Checker\EventManagementPermission\Steps;

use App\Entity\GuildEvent;
use App\Entity\User;
use App\Enum\RolesEnum;
use App\Interface\EventManagementPermissionStepInterface;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class UserHasPermissionStep implements EventManagementPermissionStepInterface
{
    public function __construct(
        private Security $security
    ) {}

    public function check(GuildEvent $guildEvent): bool
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        return $this->security->isGranted(RolesEnum::ADMIN->value)
            || ($guildEvent->getOwner() === $currentUser)
            || ($guildEvent->canMembersManageEvent() && $this->security->isGranted(RolesEnum::MEMBER->value));
    }
}
