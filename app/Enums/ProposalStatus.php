<?php

declare(strict_types=1);

namespace App\Enums;

enum ProposalStatus: string
{
    case PENDING = 'pending';
    case REJECTED = 'rejected';
    case APPROVED = 'approved';
}
