<?php

namespace App\Enum;

enum RolesEnum: string
{
    case DEV = 'ROLE_DEV';
    case ADMIN = 'ROLE_ADMIN';
    case MEMBER = 'ROLE_MEMBER';
    case TRIAL = 'ROLE_TRIAL';
    case OLD_MEMBER = 'ROLE_OLDMEMBER';
    case GUEST = 'ROLE_GUEST';

    public static function getRoleDisplayName(self $role): string
    {
        return match ($role) {
            self::DEV => 'Dev',
            self::ADMIN => 'Officier',
            self::MEMBER => 'Membre',
            self::TRIAL => 'Trial',
            self::OLD_MEMBER => 'Ancien membre',
            self::GUEST => 'Guest',
        };
    }

    /**
     * @return RolesEnum[]
     */
    public static function getActiveGuildRoles(): array
    {
        return [
            self::DEV,
            self::ADMIN,
            self::MEMBER,
            self::TRIAL,
        ];
    }
}
