<?php

declare(strict_types=1);

namespace App\Events\Proposal;

use App\Models\Proposal;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProposalWithdrawn
{
    use Dispatchable;
    use Queueable;
    use SerializesModels;

    public function __construct(public readonly Proposal $proposal) {}
}
