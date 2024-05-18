<?php

namespace App\Twig\Extension\GuildEvent;

use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\EventAttendance;
use App\Enum\AttendanceTypeEnum;
use App\Repository\GuildEventRepository;
use App\Service\GuildEvent\EventAttendanceService;
use Doctrine\ORM\EntityNotFoundException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class EventParticipationExtension extends AbstractExtension
{
    public function __construct(
        private readonly GuildEventRepository   $guildEventRepository,
        private readonly EventAttendanceService $eventAttendanceService,
    ) {}

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_event_player_count', $this->getEventPlayerCount(...)),
            new TwigFunction('does_user_have_attendance_of_type', $this->doesUserHaveAttendanceOfType(...)),
            new TwigFunction('get_attendance_icon', $this->getAttendanceIcon(...)),
        ];
    }

    public function getEventPlayerCount(int $guildEventId): int
    {
        $guildEvent = $this->getGuildEvent($guildEventId);

        return $this->eventAttendanceService->getEventPlayerCount($guildEvent);
    }

    public function doesUserHaveAttendanceOfType(int $guildEventId, string $attendanceType, int $userId): bool
    {
        $guildEvent = $this->getGuildEvent($guildEventId);
        $attendances = $this->eventAttendanceService->getAttendanceListByType($guildEvent, AttendanceTypeEnum::from($attendanceType));

        return count(array_filter(
                $attendances,
                static function ($attendance) use ($userId) {
                    return $attendance->getUser()?->getId() === $userId;
                }
            )) > 0;
    }

    public function getAttendanceIcon(AttendanceTypeEnum $attendanceType): string
    {
        return match($attendanceType) {
            AttendanceTypeEnum::PLAYER => "<span class='text-success fw-bold' title='Joueur'>P </span>",
            AttendanceTypeEnum::BACKUP => "<span class='text-warning fw-bold' title='Backup'>B </span>",
            AttendanceTypeEnum::ABSENT => "<span class='text-danger fw-bold' title='Absent'>ABS </span>",
        };
    }

    private function getGuildEvent(int $guildEventId): GuildEvent
    {
        $guildEvent = $this->guildEventRepository->find($guildEventId);

        if (!$guildEvent) {
            throw new EntityNotFoundException(sprintf('Guild event with id %d not found', $guildEventId));
        }

        return $guildEvent;
    }
}
