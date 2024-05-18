<?php

namespace App\Enum;

enum AttendanceTypeEnum: string
{
    case UNDEFINED = 'Non défini';
    case PLAYER = 'Inscrit';
    case BACKUP = 'Backup';
    case ABSENT = 'Absent';
}
