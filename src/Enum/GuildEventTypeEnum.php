<?php

namespace App\Enum;

enum GuildEventTypeEnum: string
{
    case GUILDRAID = 'Guild Raid';
    case RAID = 'Raid';
    case STRIKE = 'Strike';
    case FRACTAL = 'Fractal';
    case IRL = 'Irl';
}
