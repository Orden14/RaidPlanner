<?php

namespace App\Simple;

use App\Entity\GuildEvent;
use DateTime;

final class GraidAttendanceTableData
{
    /** @var GuildEvent[] */
    private array $weeklyGuildRaids = [];

    /** @var MemberWeeklyAttendance[] */
    private array $memberWeeklyAttendances = [];

    private DateTime $startOfWeek;
    private DateTime $endOfWeek;

    /**
     * @return GuildEvent[]
     */
    public function getWeeklyGuildRaids(): array
    {
        return $this->weeklyGuildRaids;
    }

    /**
     * @param GuildEvent[] $weeklyGuildRaids
     */
    public function setWeeklyGuildRaids(array $weeklyGuildRaids): self
    {
        $this->weeklyGuildRaids = $weeklyGuildRaids;

        return $this;
    }

    /**
     * @return MemberWeeklyAttendance[]
     */
    public function getMemberWeeklyAttendances(): array
    {
        return $this->memberWeeklyAttendances;
    }

    public function addMemberWeeklyAttendances(MemberWeeklyAttendance $memberWeeklyAttendance): self
    {
        $this->memberWeeklyAttendances[] = $memberWeeklyAttendance;

        return $this;
    }

    public function getStartOfWeek(): DateTime
    {
        return $this->startOfWeek;
    }

    public function setStartOfWeek(DateTime $startOfWeek): self
    {
        $this->startOfWeek = $startOfWeek;

        return $this;
    }

    public function getEndOfWeek(): DateTime
    {
        return $this->endOfWeek;
    }

    public function setEndOfWeek(DateTime $endOfWeek): self
    {
        $this->endOfWeek = $endOfWeek;

        return $this;
    }
}
