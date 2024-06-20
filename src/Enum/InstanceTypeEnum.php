<?php

namespace App\Enum;

enum InstanceTypeEnum: string
{
    case RAID = 'Raid';
    case STRIKE = 'Strike';
    case FRACTAL = 'Fractal';
    case DUNGEON = 'Dungeon';

    public static function getEventDisplayName(self $eventType): string
    {
        return match ($eventType) {
            self::RAID => 'Raid',
            self::STRIKE => 'Strike',
            self::FRACTAL => 'Fractale',
            self::DUNGEON => 'Donjon',
        };
    }

    public static function getMaxPlayersByType(self $eventType): int
    {
        return match ($eventType) {
            self::RAID, self::STRIKE => 10,
            self::FRACTAL, self::DUNGEON => 5,
        };
    }

    /**
     * @return string[]
     */
    public static function toArray(): array
    {
        return array_map(
            static fn (self $eventType): string => $eventType->value,
            self::cases()
        );
    }
}
