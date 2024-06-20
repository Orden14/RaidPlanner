<?php

namespace App\Builder;

use App\Entity\GuildEvent;
use App\Entity\GuildEventRelation\EventBattle;
use App\Entity\User;
use App\Enum\AttendanceTypeEnum;
use App\Enum\WeeklyGraidAttendanceTypeEnum;
use App\Repository\EventAttendanceRepository;
use App\Repository\GuildEventRepository;
use App\Service\User\UserDataService;
use App\Simple\GraidAttendanceTableData;
use App\Simple\MemberWeeklyAttendance;
use App\Util\DateHelper;

final class GraidAttendanceTableDataBuilder
{
    private GraidAttendanceTableData $graidAttendanceTable;

    public function __construct(
        private readonly UserDataService $userDataService,
        private readonly GuildEventRepository $guildEventRepository,
        private readonly EventAttendanceRepository $eventAttendanceRepository,
    ) {
        $this->graidAttendanceTable = new GraidAttendanceTableData();
    }

    public function build(): GraidAttendanceTableData
    {
        $this->graidAttendanceTable->setWeeklyGuildRaids($this->guildEventRepository->findWeeklyGuildRaids());
        $this->setDates();

        foreach ($this->userDataService->getActiveMembers() as $activeMember) {
            $this->graidAttendanceTable->addMemberWeeklyAttendances($this->getMemberWeeklyAttendance($activeMember));
        }

        return $this->graidAttendanceTable;
    }

    private function setDates(): void
    {
        $this->graidAttendanceTable->setStartOfWeek(DateHelper::getStartOfWeek());
        $this->graidAttendanceTable->setEndOfWeek(DateHelper::getEndOfWeek());
    }

    private function getMemberWeeklyAttendance(User $activeMember): MemberWeeklyAttendance
    {
        $memberWeeklyAttendance = new MemberWeeklyAttendance();
        $memberWeeklyAttendance->setUsername($activeMember->getUsername());

        foreach ($this->graidAttendanceTable->getWeeklyGuildRaids() as $guildRaid) {
            $memberWeeklyAttendance->addAttendance($this->getGraidAttendanceType($activeMember, $guildRaid));
        }

        return $memberWeeklyAttendance;
    }

    private function getGraidAttendanceType(User $activeMember, GuildEvent $guildRaid): WeeklyGraidAttendanceTypeEnum
    {
        $eventAttendance = $this->eventAttendanceRepository->findOneBy([
            'user' => $activeMember,
            'guildEvent' => $guildRaid,
        ]);

        return match ($eventAttendance?->getType()) {
            AttendanceTypeEnum::PLAYER => $this->getCorrespondingSignupTypeForPlayer($activeMember, $guildRaid),
            AttendanceTypeEnum::BACKUP => WeeklyGraidAttendanceTypeEnum::BACKUP,
            AttendanceTypeEnum::ABSENT => WeeklyGraidAttendanceTypeEnum::ABSENT,
            default => WeeklyGraidAttendanceTypeEnum::NORESPONSE,
        };
    }

    private function getCorrespondingSignupTypeForPlayer(User $activeMember, GuildEvent $guildRaid): WeeklyGraidAttendanceTypeEnum
    {
        foreach ($guildRaid->getEventBattles() as $eventBattle) {
            if (!$this->doesUserHaveASlotForBattle($activeMember, $eventBattle)) {
                return WeeklyGraidAttendanceTypeEnum::INCOMPLETESIGNUP;
            }
        }

        return WeeklyGraidAttendanceTypeEnum::SIGNEDUP;
    }

    private function doesUserHaveASlotForBattle(User $activeMember, EventBattle $eventBattle): bool
    {
        foreach ($eventBattle->getPlayerSlots() as $slot) {
            if ($slot->getPlayer() === $activeMember) {
                return true;
            }
        }

        return false;
    }
}
