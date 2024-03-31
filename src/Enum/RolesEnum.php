<?php

namespace App\Enum;

enum RolesEnum: string
{
    case ADMIN = 'ROLE_ADMIN';
    case MEMBER = 'ROLE_MEMBER';
    case GUEST = 'ROLE_GUEST';

    public function getRoleString(self $role): string
    {
        return match($role) {
            self::ADMIN => 'Officier',
            self::MEMBER => 'Membre',
            self::GUEST => 'Guest',
        };
    }
}
