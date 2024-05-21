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
    public static function toArray(): array
    {
        return array_map(
            static fn(self $type) => $type->value,
            self::cases()
        );
    }
}
