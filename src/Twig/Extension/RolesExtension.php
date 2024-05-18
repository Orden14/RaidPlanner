<?php

namespace App\Twig\Extension;

use App\Enum\RolesEnum;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class RolesExtension extends AbstractExtension
{
    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_role_display_name', $this->getRoleDisplayName(...)),
            new TwigFunction('get_role_importance', $this->getRoleImportance(...)),
        ];
    }

    public function getRoleDisplayName(RolesEnum $role): string
    {
        return RolesEnum::getRoleDisplayName($role);
    }

    public function getRoleImportance(RolesEnum $role): int
    {
        return RolesEnum::getRoleImportance($role);
    }
}
