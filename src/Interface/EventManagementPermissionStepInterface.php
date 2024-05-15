<?php

namespace App\Interface;

use App\Entity\GuildEvent;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag]
interface EventManagementPermissionStepInterface
{
    public function check(GuildEvent $guildEvent): bool;
}
