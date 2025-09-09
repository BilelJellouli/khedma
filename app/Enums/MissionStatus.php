<?php

declare(strict_types=1);

namespace App\Enums;

enum MissionStatus: string
{
    case PENDING = 'pending';
    case LIVE = 'live';
    case CANCELLED = 'cancelled';
}
