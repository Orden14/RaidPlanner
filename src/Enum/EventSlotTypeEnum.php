<?php

namespace App\Enum;

use InvalidArgumentException;

enum EventSlotTypeEnum: string
{
    case PLAYER = "Joueur";
    case BACKUP = "Backup";
    case ABSENT = "Absent";

    public static function getSlotTypeByValue(string $value): self
    {
        return match ($value) {
            self::PLAYER->value => self::PLAYER,
            self::BACKUP->value => self::BACKUP,
            self::ABSENT->value => self::ABSENT,
            default => throw new InvalidArgumentException("Invalid slot type value provided"),
        };
    }
}
