<?php

namespace App\Http\Controllers\Agent;

use App\Actions\Agent\CreateAgentContactAction;
use App\Http\Requests\Agent\CreateAgentContactRequest;
use App\Http\Resources\Agent\AgentContactResource;
use App\Models\Agent;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class StoreAgentContactsController {
    public function __invoke(
        CreateAgentContactRequest $request,
        CreateAgentContactAction $createAgentContact,
    ): JsonResponse {
        /** @var User $user */
        $user = $request->user();

        /** @var Agent $agent */
        $agent = Agent::firstWhere('user_id', $user->id);

        $agentContact = $createAgentContact->execute($agent, $request->validated());

        return response()->json([
            'data' => AgentContactResource::make($agentContact),
        ], Response::HTTP_CREATED);
    }
}
