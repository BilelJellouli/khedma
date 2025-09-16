<?php

declare(strict_types=1);

namespace App\Http\Controllers\Proposal;

use App\Actions\Proposal\RejectProposalAction;
use App\Enums\ProposalStatus;
use App\Http\Requests\Proposal\RejectProposalRequest;
use App\Models\Mission;
use App\Models\Proposal;
use Illuminate\Http\Response;

class RejectMissionProposalsController
{
    public function __invoke(
        RejectProposalRequest $request,
        RejectProposalAction $rejectProposal,
        Mission $mission,
        Proposal $proposal,
    ): Response {
        $request->user();

        if ($proposal->status === ProposalStatus::REJECTED) {
            abort(Response::HTTP_FORBIDDEN, 'already_rejected');
        }

        $rejectProposal->execute($proposal, $request->validated());

        return response()->noContent();
    }
}
