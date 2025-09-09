<?php

declare(strict_types=1);

namespace App\Enums;

enum MissionType: string
{
    case ONE_TIME = 'one_time';
    case REPETITIVE = 'repetitive';
    case PART_TIME = 'part_time';
    case FULL_TIME = 'full_time';
}
