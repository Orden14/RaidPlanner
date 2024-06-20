<?php

namespace App\Simple;

use App\Enum\WeeklyGraidAttendanceTypeEnum;

final class MemberWeeklyAttendance
{
    private string $username;

    /** @var WeeklyGraidAttendanceTypeEnum[] */
    private array $attendances = [];

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function addAttendance(WeeklyGraidAttendanceTypeEnum $attendance): self
    {
        $this->attendances[] = $attendance;

        return $this;
    }

    /**
     * @return WeeklyGraidAttendanceTypeEnum[]
     */
    public function getAttendances(): array
    {
        return $this->attendances;
    }
}
