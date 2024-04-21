<?php

namespace App\Enum;

use InvalidArgumentException;

enum RolesEnum: string
{
    case ADMIN = 'ROLE_ADMIN';
    case MEMBER = 'ROLE_MEMBER';
    case TRIAL = 'ROLE_TRIAL';
    case OLD_MEMBER = 'ROLE_OLDMEMBER';
    case GUEST = 'ROLE_GUEST';

    public static function getRoleDisplayName(self $role): string
    {
        return match($role) {
            self::ADMIN => 'Officier',
            self::MEMBER => 'Membre',
            self::TRIAL => 'Trial',
            self::OLD_MEMBER => 'Ancien membre',
            self::GUEST => 'Guest',
        };
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function getRoleFromValue(string $role): self
    {
        return match($role) {
            self::ADMIN->value => self::ADMIN,
            self::MEMBER->value => self::MEMBER,
            self::TRIAL->value => self::TRIAL,
            self::OLD_MEMBER->value => self::OLD_MEMBER,
            self::GUEST->value => self::GUEST,
            default => throw new InvalidArgumentException('Invalid role value provided.'),
        };
    }
}
