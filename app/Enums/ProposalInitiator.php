<?php

namespace App\Enums;

enum ProposalInitiator: string
{
    case AGENT = 'agent';
    case CUSTOMER = 'customer';
    case SYSTEM = 'system';
}
