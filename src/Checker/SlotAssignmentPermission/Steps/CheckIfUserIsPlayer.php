<?php

namespace App\Checker\SlotAssignmentPermission\Steps;

use App\Entity\GuildEventRelation\EventEncounter;
use App\Entity\User;
use App\Enum\AttendanceTypeEnum;
use App\Interface\SlotAssignmentPermissionStepInterface;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class CheckIfUserIsPlayer implements SlotAssignmentPermissionStepInterface
{
    public function __construct(
        private Security $security
    ) {}

    public function check(EventEncounter $eventEncounter): bool
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        foreach ($eventEncounter->getGuildEvent()?->getEventAttendances() as $eventAttendance) {
            if ($eventAttendance->getUser() === $currentUser) {
                return $eventAttendance->getType() === AttendanceTypeEnum::PLAYER;
            }
        }

        return false;
    }
}
