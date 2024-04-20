<?php

namespace App\Enum;

use InvalidArgumentException;

enum BuildStatusEnum: string
{
    case META = 'Meta';
    case OUTDATED = 'Outdated';
    case NOT_META = 'Hors meta';

    /**
     * @throws InvalidArgumentException
     */
    public static function getStatusFromValue(string $status): self
    {
        return match($status) {
            self::META->value => self::META,
            self::OUTDATED->value => self::OUTDATED,
            self::NOT_META->value => self::NOT_META,
            default => throw new InvalidArgumentException('Invalid status value provided.'),
        };
    }
}
