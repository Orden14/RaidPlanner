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
}
