<?php

namespace App\Checker\SlotAssignmentPermission\Steps;

use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\EventBattle;
use App\Entity\User;
use App\Enum\AttendanceTypeEnum;
use App\Enum\InstanceTypeEnum;
use App\Interface\SlotAssignmentPermissionStepInterface;
use App\Service\GuildEvent\EventAttendanceService;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class CheckEventIsNotFullStep implements SlotAssignmentPermissionStepInterface
{
    public function __construct(
        private Security               $security,
        private EventAttendanceService $eventAttendanceService,
    ) {}

    public function check(EventBattle $eventBattle): bool
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        /** @var GuildEvent $guildEvent */
        $guildEvent = $eventBattle->getGuildEvent();

        return $this->eventAttendanceService->isUserAttendingAsPlayer($guildEvent, $currentUser)
            || count($this->eventAttendanceService->getAttendanceListByType($guildEvent, AttendanceTypeEnum::PLAYER)) < InstanceTypeEnum::getMaxPlayersByType($guildEvent->getType());
    }
}
