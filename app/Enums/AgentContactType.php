<?php

namespace App\Enums;

enum AgentContactType: string
{
    case FACEBOOK = 'facebook';
    case PHONE = 'phone';
    case WHATSAPP = 'whatsapp';
    case EMAIL = 'email';
}
