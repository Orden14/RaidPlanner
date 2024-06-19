<?php

namespace App\Twig\Extension\GuildEvent;

use App\Enum\WeeklyGraidAttendanceTypeEnum;
use Override;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class GraidAttendanceTableExtension extends AbstractExtension
{
    /**
     * @return TwigFunction[]
     */
    #[Override]
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_graid_attendance_string', $this->getGraidAttendanceString(...)),
        ];
    }

    public function getGraidAttendanceString(WeeklyGraidAttendanceTypeEnum $graidAttendanceType): string
    {
        return match ($graidAttendanceType) {
            WeeklyGraidAttendanceTypeEnum::SIGNEDUP => 'Inscrit',
            WeeklyGraidAttendanceTypeEnum::INCOMPLETESIGNUP => 'Incomplet',
            WeeklyGraidAttendanceTypeEnum::BACKUP => 'Backup',
            WeeklyGraidAttendanceTypeEnum::ABSENT => 'Absent',
            WeeklyGraidAttendanceTypeEnum::NORESPONSE => ''
        };
    }
}
