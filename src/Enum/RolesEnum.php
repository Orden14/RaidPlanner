<?php

namespace App\Enum;

enum RolesEnum: string
{
    case ADMIN = 'ROLE_ADMIN';
    case MEMBER = 'ROLE_MEMBER';
    case TRIAL = 'ROLE_TRIAL';
    case OLD_MEMBER = 'ROLE_OLDMEMBER';
    case GUEST = 'ROLE_GUEST';

    public static function getRoleDisplayName(self $role): string
    {
        return match ($role) {
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
            self::ADMIN,
            self::MEMBER,
            self::TRIAL,
        ];
    }

    public static function getRoleImportance(self $role): int
    {
        return match ($role) {
            self::ADMIN => 1,
            self::MEMBER => 2,
            self::TRIAL => 3,
            self::OLD_MEMBER => 4,
            self::GUEST => 5,
        };
    }

    /**
     * @return string[]
     */
    public static function toArray(): array
    {
        return array_map(
            static fn(RolesEnum $role): string => $role->value,
            self::cases()
        );
    }
}
