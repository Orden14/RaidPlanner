<?php

namespace App\Checker\SlotAssignmentPermission\Steps;

use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\EventBattle;
use App\Enum\AttendanceTypeEnum;
use App\Enum\InstanceTypeEnum;
use App\Interface\SlotAssignmentPermissionStepInterface;
use App\Service\GuildEvent\EventAttendanceService;

final readonly class CheckEventIsNotFullStep implements SlotAssignmentPermissionStepInterface
{
    public function __construct(
        private EventAttendanceService $eventAttendanceService
    ) {}

    public function check(EventBattle $eventBattle): bool
    {
        /** @var GuildEvent $guildEvent */
        $guildEvent = $eventBattle->getGuildEvent();

        return count($this->eventAttendanceService->getAttendanceListByType($guildEvent, AttendanceTypeEnum::PLAYER)) < InstanceTypeEnum::getMaxPlayersByType($guildEvent->getType());
    }
}
