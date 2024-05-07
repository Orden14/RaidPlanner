<?php

namespace App\Enum;

use InvalidArgumentException;

enum LogTypeEnum
{
    case REGISTER;
    case USER_UPDATE;
    case BUILD_CREATE;
    case BUILD_UPDATE;
    case BUILD_STATUS_UPDATE;
    case BUILD_DELETE;
    case BUILD_MESSAGE_NEW;
    case BUILD_MESSAGE_DELETE;
    case EVENT_CREATE;
    case EVENT_UPDATE;
    case EVENT_REGISTER;
    case EVENT_DESIST;

    /**
     * @thows InvalidArgumentException
     */
    public static function getTypeFromValue(string $type): self
    {
        $enumCases = array_map(static fn($case) => $case->name, self::cases());

        if (!in_array($type, $enumCases, true)) {
            throw new InvalidArgumentException('Invalid log type value provided.');
        }

        return self::$type();
    }
}
