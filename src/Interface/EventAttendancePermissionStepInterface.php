<?php

namespace App\Interface;

use App\Entity\GuildEvent;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag]
interface EventAttendancePermissionStepInterface
{
    public function check(GuildEvent $guildEvent): bool;
}
