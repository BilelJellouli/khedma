<?php

declare(strict_types=1);

namespace App\Http\Controllers\Mission;

use App\Actions\Mission\UpdateMissionAction;
use App\Http\Requests\Mission\UpdateMissionRequest;
use App\Http\Resources\MissionResource;
use App\Models\Mission;
use Illuminate\Http\JsonResponse;

class UpdateMissionsController
{
    public function __invoke(
        UpdateMissionAction $updateMission,
        UpdateMissionRequest $request,
        Mission $mission
    ): JsonResponse {
        $mission = $updateMission->execute(
            $mission,
            [
                ...$request->validated(),
                'status' => $request->status(),
            ]
        );

        return response()->json([
            'data' => MissionResource::make($mission->loadMissing('service')),
        ]);
    }
}
