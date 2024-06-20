<?php

namespace App\Checker\SlotAssignmentPermission\Steps;

use App\Entity\GuildEventRelation\EventBattle;
use App\Entity\GuildEventRelation\PlayerSlot;
use App\Entity\User;
use App\Enum\AttendanceTypeEnum;
use App\Interface\SlotAssignmentPermissionStepInterface;
use Override;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class CheckUserIsNotAbsentStep implements SlotAssignmentPermissionStepInterface
{
    public function __construct(
        private Security $security
    ) {
    }

    #[Override]
    public function check(PlayerSlot $playerSlot): bool
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        /** @var EventBattle $eventBattle */
        $eventBattle = $playerSlot->getEventBattle();

        foreach ($eventBattle->getGuildEvent()?->getEventAttendances() as $eventAttendance) {
            if ($eventAttendance->getUser() === $currentUser) {
                return $eventAttendance->getType() !== AttendanceTypeEnum::ABSENT;
            }
        }

        return true;
    }
}
