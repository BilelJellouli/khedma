<?php

declare(strict_types=1);

namespace App\Actions\Mission;

use App\Events\Mission\MissionCreated;
use App\Models\Mission;
use App\Models\User;

class CreateMissionAction
{
    public function execute(User $customer, array $data): Mission
    {
        /** @var Mission $mission */
        $mission = $customer->missions()->create($data);

        MissionCreated::dispatch($mission);

        return $mission;
    }
}
