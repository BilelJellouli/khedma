<?php

declare(strict_types=1);

namespace App\Events\Agent;

use App\Models\AgentContact;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AgentContactCreated
{
    use Dispatchable;
    use Queueable;
    use SerializesModels;

    public function __construct(public readonly AgentContact $contact) {}
}
