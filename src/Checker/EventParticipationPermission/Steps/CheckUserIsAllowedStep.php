<?php

namespace App\Checker\EventParticipationPermission\Steps;

use App\Entity\GuildEvent;
use App\Entity\User;
use App\Enum\RolesEnum;
use App\Interface\EventParticipationPermissionStepInterface;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class CheckUserIsAllowedStep implements EventParticipationPermissionStepInterface
{
    public function __construct(
        private Security $security
    ) {}

    public function check(GuildEvent $guildEvent): bool
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        return $this->security->isGranted(RolesEnum::TRIAL->value)
            || $guildEvent->getOwner() === $currentUser
            || ($guildEvent->isOldMembersAllowed() && $this->security->isGranted(RolesEnum::OLD_MEMBER->value));
    }
}
