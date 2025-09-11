<?php

declare(strict_types=1);

namespace App\Actions\Mission;

use App\Events\Mission\MissionDeleted;
use App\Models\Mission;

class DeleteMissionAction
{
    public function execute(Mission $mission): void
    {
        $mission->delete();

        MissionDeleted::dispatch($mission);
    }
}
