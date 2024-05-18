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
            self::GUEST => 'Nouveau compte',
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

    public static function getRoleImportance(self $role): int
    {
        return match ($role) {
            self::DEV => 1,
            self::ADMIN => 2,
            self::MEMBER => 3,
            self::TRIAL => 4,
            self::OLD_MEMBER => 5,
            self::GUEST => 6,
        };
    }
}
