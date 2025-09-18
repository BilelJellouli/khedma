<?php

declare(strict_types=1);

namespace App\Http\Controllers\Agent;

use App\Actions\Agent\CreateAgentAction;
use App\Http\Requests\Agent\CreateAgentRequest;
use App\Http\Resources\Agent\AgentResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class StoreAgentsController
{
    public function __invoke(
        CreateAgentRequest $request,
        CreateAgentAction $createAgent
    ): JsonResponse {
        /** @var User $user */
        $user = $request->user();

        $agent = $createAgent->execute(
            $user,
            $request->services(),
            $request->validated()
        );

        return response()->json([
            'data' => AgentResource::make($agent),
        ], Response::HTTP_CREATED);
    }
}
