<?php

namespace App\Service\GuildEvent;

use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\EventAttendance;
use App\Entity\User;
use App\Enum\AttendanceTypeEnum;
use App\Repository\EventAttendanceRepository;

final readonly class EventAttendanceDataService
{
    public function __construct(
        private EventAttendanceRepository $eventAttendanceRepository
    ) {
    }

    /**
     * @return array<int, EventAttendance[]>
     */
    public function getCombinedAttendanceList(GuildEvent $guildEvent): array
    {
        $players = $this->getAttendanceListByType($guildEvent, AttendanceTypeEnum::PLAYER);
        $backups = $this->getAttendanceListByType($guildEvent, AttendanceTypeEnum::BACKUP);
        $absents = $this->getAttendanceListByType($guildEvent, AttendanceTypeEnum::ABSENT);

        $combined = array_merge($players, $backups, $absents);

        return array_chunk($combined, (int) ceil(count($combined) / 2) ?: 1);
    }

    public function getEventPlayerCount(GuildEvent $guildEvent): int
    {
        return count(array_filter(
            $guildEvent->getEventAttendances()->toArray(),
            static fn ($attendance) => $attendance->getType() === AttendanceTypeEnum::PLAYER
        ));
    }

    /**
     * @return EventAttendance[]
     */
    public function getAttendanceListByType(GuildEvent $guildEvent, AttendanceTypeEnum $attendanceType): array
    {
        return array_filter(
            $guildEvent->getEventAttendances()->toArray(),
            static fn ($attendance) => $attendance->getType() === $attendanceType
        );
    }

    public function isUserAttendingAsPlayer(GuildEvent $guildEvent, User $user): bool
    {
        return count($this->eventAttendanceRepository->findEventAttendancesByTypesForUser($guildEvent, $user, [AttendanceTypeEnum::PLAYER])) > 0;
    }
}
