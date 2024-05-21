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
        return match ($status) {
            self::META->value => 'build-status-meta',
            self::NOT_META->value => 'build-status-not-meta',
            self::OUTDATED->value => 'build-status-outdated',
            default => throw new InvalidArgumentException('Invalid status value provided.'),
        };
    }

    /**
     * @return String[]
     */
    public static function toArray(): array
    {
        return array_map(
            static fn (BuildStatusEnum $status): string => $status->value,
            self::cases()
        );
    }
}
