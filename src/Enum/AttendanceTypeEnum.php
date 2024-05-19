<?php

namespace App\Enum;

enum AttendanceTypeEnum: string
{
    case UNDEFINED = 'Non défini';
    case PLAYER = 'Joueur';
    case BACKUP = 'Backup';
    case ABSENT = 'Absent';
}
