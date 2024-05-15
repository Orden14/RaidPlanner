<?php

namespace App\Service\GuildEvent;

use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\EventAttendance;
use App\Enum\AttendanceTypeEnum;

final readonly class EventAttendanceService
{
    public function getEventPlayerCount(GuildEvent $guildEvent): int
    {
        return count(array_filter(
            $guildEvent->getEventAttendances()->toArray(),
            static function ($attendance) {
                return $attendance->getType() === AttendanceTypeEnum::PLAYER;
            }
        ));
    }

    /**
     * @return EventAttendance[]
     */
    public function getAttendanceListByType(GuildEvent $guildEvent, AttendanceTypeEnum $attendanceType): array
    {
        return array_filter(
            $guildEvent->getEventAttendances()->toArray(),
            static function ($attendance) use ($attendanceType) {
                return $attendance->getType() === $attendanceType;
            }
        );
    }
}
