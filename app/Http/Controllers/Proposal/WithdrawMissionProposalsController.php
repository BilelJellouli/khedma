<?php

declare(strict_types=1);

namespace App\Http\Controllers\Proposal;

use App\Actions\Proposal\WithdrawProposalAction;
use App\Enums\UserRole;
use App\Models\Mission;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class WithdrawMissionProposalsController
{
    public function __invoke(
        Request $request,
        WithdrawProposalAction $withdrawProposal,
        Mission $mission,
        Proposal $proposal,
    ): Response {
        /** @var User $agent */
        $agent = $request->user();

        if ($agent->role !== UserRole::AGENT) {
            abort(SymfonyResponse::HTTP_FORBIDDEN, 'only_agents');
        }

        $proposal->loadMissing('agent');

        if ($proposal->agent->user_id !== $agent->id) {
            abort(SymfonyResponse::HTTP_FORBIDDEN, 'can_not_edit_proposal');
        }

        $withdrawProposal->execute($proposal);

        return response()->noContent();
    }
}
