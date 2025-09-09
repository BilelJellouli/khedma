<?php

declare(strict_types=1);

namespace App\Enums;

enum AgentContactType: string
{
    case FACEBOOK = 'facebook';
    case PHONE = 'phone';
    case WHATSAPP = 'whatsapp';
    case EMAIL = 'email';
}
