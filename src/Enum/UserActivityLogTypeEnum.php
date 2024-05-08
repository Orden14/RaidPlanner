<?php

namespace App\Enum;

use InvalidArgumentException;

enum UserActivityLogTypeEnum: string
{
    case USER = 'Utilisateurs';
    case GUILD_EVENT = 'Evenements';
    case BUILD = 'Builds';

    public static function getLogTypeFromValue(string $value): self
    {
        return match ($value) {
            self::USER->value => self::USER,
            self::GUILD_EVENT->value => self::GUILD_EVENT,
            self::BUILD->value => self::BUILD,
            default => throw new InvalidArgumentException("Invalid log type value provided.")
        };
    }

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
