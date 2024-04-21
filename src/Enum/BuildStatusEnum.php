<?php

namespace App\Enum;

use InvalidArgumentException;

enum BuildStatusEnum: string
{
    case META = 'Meta';
    case OUTDATED = 'Outdated';
    case NOT_META = 'Hors meta';

    public static function getStatusStyleClassName(string $status): string
    {
        return match($status) {
            self::META->value => 'build-status-meta',
            self::NOT_META->value => 'build-status-not-meta',
            self::OUTDATED->value => 'build-status-outdated',
            default => throw new InvalidArgumentException('Invalid status value provided.'),
        };
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function getStatusFromValue(string $status): self
    {
        return match($status) {
            self::META->value => self::META,
            self::NOT_META->value => self::NOT_META,
            self::OUTDATED->value => self::OUTDATED,
            default => throw new InvalidArgumentException('Invalid status value provided.'),
        };
    }

    /**
     * @return String[]
     */
    public static function toArray(): array
    {
        return [
            self::META->value,
            self::OUTDATED->value,
            self::NOT_META->value,
        ];
    }
}
