<?php

declare(strict_types=1);

namespace App\Events\Mission;

use App\Models\Mission;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MissionDeleted
{
    use Dispatchable;
    use Queueable;
    use SerializesModels;

    public function __construct(public readonly Mission $mission) {}
}
