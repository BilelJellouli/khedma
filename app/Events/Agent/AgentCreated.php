<?php

declare(strict_types=1);

namespace App\Events\Agent;

use App\Models\Agent;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AgentCreated
{
    use Dispatchable;
    use Queueable;
    use SerializesModels;

    public function __construct(public readonly Agent $agent) {}
}
