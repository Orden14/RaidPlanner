<?php

namespace App\Enum;

use InvalidArgumentException;

enum GuildEventTypeEnum: string
{
    case GUILDRAID = 'Guild Raid';
    case RAID = 'Raid';
    case STRIKE = 'Strike';
    case FRACTAL = 'Fractal';
    case DUNGEON = 'Dungeon';
    case IRL = 'Irl';

    public static function getEventDisplayName(self $eventType): string
    {
        return match($eventType) {
            self::GUILDRAID => 'Guild Raid',
            self::RAID => 'Raid',
            self::STRIKE => 'Strike',
            self::FRACTAL => 'Fractale',
            self::DUNGEON => 'Donjon',
            self::IRL => 'Irl',
        };
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function getEventTypeFromValue(string $value): self
    {
        return match ($value) {
            self::GUILDRAID->value => self::GUILDRAID,
            self::RAID->value => self::RAID,
            self::STRIKE->value => self::STRIKE,
            self::FRACTAL->value => self::FRACTAL,
            self::DUNGEON->value => self::DUNGEON,
            self::IRL->value => self::IRL,
            default => throw new InvalidArgumentException("Invalid event type value provided.")
        };
    }
}
