<?php

namespace App\Enum;

enum DoctrineEventTypeEnum
{
    case POST_PERSIST;
    case POST_UPDATE;
    case POST_REMOVE;
}
