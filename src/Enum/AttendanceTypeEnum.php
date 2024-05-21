<?php

namespace App\Enum;

enum AttendanceTypeEnum: string
{
    case UNDEFINED = 'Non défini';
    case PLAYER = 'Joueur';
    case BACKUP = 'Backup';
    case ABSENT = 'Absent';

    /**
     * @return string[]
     */
    public static function toArray(): array
    {
        return array_map(
            static fn(self $type): string => $type->value,
            self::cases()
        );
    }
}
