<?php

namespace App\Enum;

use InvalidArgumentException;

enum RolesEnum: string
{
    case ADMIN = 'ROLE_ADMIN';
    case MEMBER = 'ROLE_MEMBER';
    case OLD_MEMBER = 'ROLE_OLD_MEMBER';
    case GUEST = 'ROLE_GUEST';

    public static function getRoleDisplayName(self $role): string
    {
        return match($role) {
            self::ADMIN => 'Officier',
            self::MEMBER => 'Membre',
            self::OLD_MEMBER => 'Ancien membre',
            self::GUEST => 'Guest',
        };
    }

    public static function getRoleFromValue(string $role): self
    {
        return match($role) {
            self::ADMIN->value => self::ADMIN,
            self::MEMBER->value => self::MEMBER,
            self::OLD_MEMBER->value => self::OLD_MEMBER,
            self::GUEST->value => self::GUEST,
            default => throw new InvalidArgumentException('Invalid role value provided.'),
        };
    }
}
