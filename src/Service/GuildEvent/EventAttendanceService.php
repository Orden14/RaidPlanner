<?php

namespace App\Service\GuildEvent;

use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\EventAttendance;
use App\Entity\User;
use App\Enum\AttendanceTypeEnum;
use App\Repository\EventAttendanceRepository;

final readonly class EventAttendanceService
{
    public function __construct(
        private EventAttendanceRepository $eventAttendanceRepository
    ) {}

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

    public function isUserAttendingAsPlayer(GuildEvent $guildEvent, User $user): bool
    {
        return count($this->eventAttendanceRepository->findEventAttendancesByTypesForPlayer($guildEvent, $user, [AttendanceTypeEnum::PLAYER])) > 0;
    }
}
