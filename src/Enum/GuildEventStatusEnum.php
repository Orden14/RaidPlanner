<?php

namespace App\Enum;

enum GuildEventStatusEnum: string
{
    case OPEN = 'Ouvert';
    case CANCELLED = 'Annulé';

    /**
     * @return string[]
     */
    public static function toArray(): array
    {
        return array_map(
            static fn(self $status): string => $status->value,
            self::cases()
        );
    }
}
