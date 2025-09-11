<?php

namespace App\Http\Controllers\Mission;

use App\Actions\Mission\DeleteMissionAction;
use App\Enums\UserRole;
use App\Http\Resources\MissionResource;
use App\Models\Mission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class DeleteMissionsController
{
    public function __invoke(
        Request $request,
        DeleteMissionAction $deleteMission,
        Mission $mission
    ): Response {
        $user = $request->user();

        if ($user->role !== UserRole::ADMIN && $user->id !== $mission->customer_id) {
            abort(HttpResponse::HTTP_FORBIDDEN, __(''));
        }

        $deleteMission->execute($mission);

        return response()->noContent();
    }
}
