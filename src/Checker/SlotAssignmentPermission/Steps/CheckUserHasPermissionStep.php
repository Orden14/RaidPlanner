<?php

namespace App\Checker\SlotAssignmentPermission\Steps;

use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\EventEncounter;
use App\Enum\RolesEnum;
use App\Interface\SlotAssignmentPermissionStepInterface;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class CheckUserHasPermissionStep implements SlotAssignmentPermissionStepInterface
{
    public function __construct(
        private Security $security
    ) {}

    public function check(EventEncounter $eventEncounter): bool
    {
        /** @var GuildEvent $guildEvent */
        $guildEvent = $eventEncounter->getGuildEvent();

        return $this->security->isGranted(RolesEnum::TRIAL->value)
            || ($guildEvent->isOldMembersAllowed() && $this->security->isGranted(RolesEnum::OLD_MEMBER->value));
    }
}
