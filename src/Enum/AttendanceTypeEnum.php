<?php

namespace App\Enum;

enum AttendanceTypeEnum: string
{
    case PLAYER = 'Inscrit';
    case BACKUP = 'Backup';
    case ABSENT = 'Absent';
}
