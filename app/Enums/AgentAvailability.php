<?php

declare(strict_types=1);

namespace App\Enums;

enum AgentAvailability: string
{
    case UNAVAILABLE = 'unavailable';
    case PART_TIME = 'part_time';
    case FULL_TIME = 'full_time';
    case ONE_TIME = 'one_time';
}
