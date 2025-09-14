<?php

declare(strict_types=1);

namespace App\Enums;

enum PricingUnit: string
{
    case PER_HOUR = 'per_hour';
    case PER_DAY = 'per_day';
    case PER_MONTH = 'per_month';
    case TOTAL = 'total';
}
