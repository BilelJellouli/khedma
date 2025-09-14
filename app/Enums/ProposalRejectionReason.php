<?php

declare(strict_types=1);

namespace App\Enums;

enum ProposalRejectionReason: string
{
    case MISSION_CANCELLED = 'mission_canceled';
    case BUDGET = 'budget';
    case SELECTED_ANOTHER_AGENT = 'selected_another_agent';
    case OTHERS = 'others';
}
