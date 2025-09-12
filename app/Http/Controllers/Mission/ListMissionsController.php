<?php

namespace App\Http\Controllers\Mission;

use App\Enums\UserRole;
use App\Http\Resources\MissionResource;
use App\Models\Mission;
use App\Models\User;
use Illuminate\Http\Request;

class ListMissionsController
{
    public function __invoke(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $missionsQuery = Mission::query();

        if ($user->role === UserRole::CUSTOMER) {
            $missionsQuery->ofUser($user);
        }

        if ($user->role === UserRole::AGENT) {
            $missionsQuery->live();
        }

        return response()->json([
            'data' => MissionResource::collection($missionsQuery->get())
        ]);
    }
}
