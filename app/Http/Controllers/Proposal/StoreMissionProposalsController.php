<?php

declare(strict_types=1);

namespace App\Http\Controllers\Proposal;

use App\Actions\Proposal\CreateProposalAction;
use App\Enums\ProposalInitiator;
use App\Http\Requests\Proposal\CreateProposalRequest;
use App\Http\Resources\ProposalResource;
use App\Models\Agent;
use App\Models\Mission;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class StoreMissionProposalsController
{
    public function __invoke(
        CreateProposalRequest $request,
        CreateProposalAction $createProposal,
        Mission $mission
    ): JsonResponse {
        /** @var User $user */
        $user = $request->user();

        // TODO move to middleware
        if ($mission->customer_id === $user->id) {
            abort(Response::HTTP_FORBIDDEN, 'can_not_make_proposal_to_yourself');
        }

        /** @var Agent $agent */
        $agent = Agent::where('user_id', $user->id)->first();

        if ($mission->proposals()->where('agent_id', $agent->id)->exists()) {
            abort(Response::HTTP_FORBIDDEN, 'can_not_make_more_propositions_for_this_mission');
        }

        $proposal = $createProposal->execute($agent, $mission, [
            ...$request->validated(),
            'initiator' => ProposalInitiator::AGENT,
        ]);

        return response()->json([
            'data' => ProposalResource::make($proposal),
        ], Response::HTTP_CREATED);
    }
}
