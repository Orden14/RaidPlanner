<?php

namespace App\Enum;

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

    public static function getMaxPlayersByType(self $eventType): int
    {
        return match($eventType) {
            self::GUILDRAID, self::RAID, self::STRIKE => 10,
            self::FRACTAL, self::DUNGEON => 5,
            self::IRL => 0,
        };
    }
}
