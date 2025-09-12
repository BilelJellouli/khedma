<?php

namespace App\Http\Controllers\Mission;

use App\Enums\UserRole;
use App\Http\Resources\MissionResource;
use App\Models\Mission;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShowMissionsController
{
    public function __invoke(
        Request $request,
        Mission $mission
    ): JsonResponse {
        /** @var User $user */
        $user = $request->user();

        if ($user->role !== UserRole::AGENT) {
            $mission->loadMissing(['proposals']);
        }

        if ($mission->customer_id !== $user->id) {
            $mission->loadMissing('customer');
        }

        return response()->json([
            'data' => MissionResource::make($mission->loadMissing('service')),
        ]);
    }
}
