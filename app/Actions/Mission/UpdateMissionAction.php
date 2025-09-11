<?php

declare(strict_types=1);

namespace App\Actions\Mission;

use App\Events\Mission\MissionUpdated;
use App\Models\Mission;

class UpdateMissionAction
{
    public function execute(Mission $mission, array $data): Mission
    {
        $mission->update($data);

        MissionUpdated::dispatch($mission);

        return $mission;
    }
}
