<?php

namespace App\Enum;

enum WeeklyGraidAttendanceTypeEnum: string
{
    case SIGNEDUP = 'signed-up';
    case INCOMPLETESIGNUP = 'incomplete-signup';
    case BACKUP = 'backup';
    case ABSENT = 'absent';
    case NORESPONSE = 'no-response';
}
