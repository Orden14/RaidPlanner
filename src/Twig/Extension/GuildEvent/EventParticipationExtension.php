<?php

namespace App\Twig\Extension\GuildEvent;

use App\Checker\EventSignupPermission\EventSignupChecker;
use App\Checker\SlotAssignmentPermission\SlotAssignmentChecker;
use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\EventAttendance;
use App\Enum\AttendanceTypeEnum;
use App\Repository\EventBattleRepository;
use App\Repository\GuildEventRepository;
use App\Service\GuildEvent\EventAttendanceService;
use Doctrine\ORM\EntityNotFoundException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EventParticipationExtension extends AbstractExtension
{
    public function __construct(
        private readonly EventSignupChecker     $eventSignupChecker,
        private readonly GuildEventRepository   $guildEventRepository,
        private readonly EventBattleRepository  $eventBattleRepository,
        private readonly SlotAssignmentChecker  $slotAssignmentChecker,
        private readonly EventAttendanceService $eventAttendanceService,
    ) {}

    /**
     * @return TwigFunction[]
     */
    final public function getFunctions(): array
    {
        return [
            new TwigFunction('can_user_take_slot', $this->canUserTakeSlot(...)),
            new TwigFunction('can_user_signup', $this->canUserSignup(...)),
            new TwigFunction('get_event_player_count', $this->getEventPlayerCount(...)),
            new TwigFunction('get_attendance_list_by_type', $this->getAttendanceListByType(...)),
            new TwigFunction('does_user_have_attendance_of_type', $this->doesUserHaveAttendanceOfType(...)),
        ];
    }

    /**
     * @throws EntityNotFoundException
     */
    final public function canUserTakeSlot(int $eventBattleId): bool
    {
        $eventBattle = $this->eventBattleRepository->find($eventBattleId);

        if (!$eventBattle) {
            throw new EntityNotFoundException(sprintf('Event battle with id %d not found', $eventBattleId));
        }

        return $this->slotAssignmentChecker->checkIfUserCanTakeSlot($eventBattle);
    }

    final public function canUserSignup(int $guildEventId): bool
    {
        $guildEvent = $this->getGuildEvent($guildEventId);

        return $this->eventSignupChecker->checkIfUserCanSignup($guildEvent);
    }

    final public function getEventPlayerCount(int $guildEventId): int
    {
        $guildEvent = $this->getGuildEvent($guildEventId);

        return $this->eventAttendanceService->getEventPlayerCount($guildEvent);
    }

    /**
     * @return EventAttendance[]
     */
    final public function getAttendanceListByType(int $guildEventId, string $attendanceType): array
    {
        $guildEvent = $this->getGuildEvent($guildEventId);

        return $this->eventAttendanceService->getAttendanceListByType($guildEvent, AttendanceTypeEnum::from($attendanceType));
    }

    final public function doesUserHaveAttendanceOfType(int $guildEventId, string $attendanceType, int $userId): bool
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

    private function getGuildEvent(int $guildEventId): GuildEvent
    {
        $guildEvent = $this->guildEventRepository->find($guildEventId);

        if (!$guildEvent) {
            throw new EntityNotFoundException(sprintf('Guild event with id %d not found', $guildEventId));
        }

        return $guildEvent;
    }
}
