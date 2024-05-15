<?php

namespace App\Checker\EventSignupPermission\Steps;

use App\Entity\GuildEvent;
use App\Enum\AttendanceTypeEnum;
use App\Enum\InstanceTypeEnum;
use App\Interface\EventAttendancePermissionStepInterface;
use App\Repository\EventAttendanceRepository;

final readonly class EventHasFreePlayerAttendanceSlotStep implements EventAttendancePermissionStepInterface
{
    public function __construct(
        private EventAttendanceRepository $eventAttendanceRepository
    ) {}

    public function check(GuildEvent $guildEvent): bool
    {
        $attendingPlayerCount = count($this->eventAttendanceRepository->findEventAttendancesByType($guildEvent, AttendanceTypeEnum::PLAYER));

        return $attendingPlayerCount < InstanceTypeEnum::getMaxPlayersByType($guildEvent->getType());
    }
}
