<?php

namespace App\Enum;

enum UserActivityLogTypeEnum: string
{
    case USER = 'Utilisateurs';
    case GUILD_EVENT = 'Evenements';
    case BUILD = 'Builds';

    /**
     * @return string[]
     */
    public static function getLogTypesAsArray(): array
    {
        return [
            self::USER->value,
            self::GUILD_EVENT->value,
            self::BUILD->value
        ];
    }
}
