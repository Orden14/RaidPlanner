<?php

namespace App\Twig\Extension\GuildEvent;

use App\Entity\GuildEvent;
use App\Enum\AttendanceTypeEnum;
use App\Enum\InstanceTypeEnum;
use App\Service\GuildEvent\EventAttendanceDataService;
use Override;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class EventParticipationExtension extends AbstractExtension
{
    public function __construct(
        private readonly EventAttendanceDataService $eventAttendanceDataService,
    ) {}

    /**
     * @return TwigFunction[]
     */
    #[Override]
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_event_player_count', $this->getEventPlayerCount(...)),
            new TwigFunction('get_event_max_player_count', $this->getEventMaxPlayerCount(...)),
            new TwigFunction('does_user_have_attendance_of_type', $this->doesUserHaveAttendanceOfType(...)),
            new TwigFunction('get_attendance_icon', $this->getAttendanceIcon(...)),
        ];
    }

    public function getEventPlayerCount(GuildEvent $guildEvent): int
    {
        return $this->eventAttendanceDataService->getEventPlayerCount($guildEvent);
    }

    public function getEventMaxPlayerCount(GuildEvent $guildEvent): int
    {
        return InstanceTypeEnum::getMaxPlayersByType($guildEvent->getType());
    }

    public function doesUserHaveAttendanceOfType(GuildEvent $guildEvent, string $attendanceType, int $userId): bool
    {
        $attendances = $this->eventAttendanceDataService->getAttendanceListByType($guildEvent, AttendanceTypeEnum::from($attendanceType));

        return count(array_filter(
                $attendances,
                static function ($attendance) use ($userId) {
                    return $attendance->getUser()?->getId() === $userId;
                }
            )) > 0;
    }

    public function getAttendanceIcon(AttendanceTypeEnum $attendanceType): string
    {
        return match ($attendanceType) {
            AttendanceTypeEnum::PLAYER => "<span class='text-success fw-bold' title='Joueur'>P </span>",
            AttendanceTypeEnum::BACKUP => "<span class='text-warning fw-bold' title='Backup'>B </span>",
            AttendanceTypeEnum::ABSENT => "<span class='text-danger fw-bold' title='Absent'>A </span>",
            AttendanceTypeEnum::UNDEFINED => ""
        };
    }
}
