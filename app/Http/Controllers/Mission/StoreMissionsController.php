<?php

declare(strict_types=1);

namespace App\Http\Controllers\Mission;

use App\Actions\Mission\CreateMissionAction;
use App\Http\Requests\Mission\CreateMissionRequest;
use App\Http\Resources\MissionResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class StoreMissionsController
{
    public function __invoke(
        CreateMissionAction $createMission,
        CreateMissionRequest $request,
    ): JsonResponse {
        /** @var User $customer */
        $customer = $request->user();

        $mission = $createMission->execute(
            $customer,
            [
                ...$request->validated(),
                'status' => $request->status(),
            ]
        );

        return response()->json([
            'data' => MissionResource::make($mission->loadMissing('service')),
        ], Response::HTTP_CREATED);
    }
}
