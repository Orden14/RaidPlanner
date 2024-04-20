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

    public static function getStatusStyleClassName(string $status): string
    {
        return match($status) {
            self::META->value => 'build-status-meta',
            self::NOT_META->value => 'build-status-not-meta',
            self::OUTDATED->value => 'build-status-outdated',
            default => throw new InvalidArgumentException('Invalid status value provided.'),
        };
    }
}
