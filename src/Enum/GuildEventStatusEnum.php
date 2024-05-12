<?php

namespace App\Enum;

enum GuildEventStatusEnum: string
{
    case OPEN = 'Ouvert';
    case CANCELLED = 'Annulé';
}
