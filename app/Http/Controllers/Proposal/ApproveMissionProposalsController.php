<?php

declare(strict_types=1);

namespace App\Http\Controllers\Proposal;

use App\Actions\Proposal\ApproveProposalAction;
use App\Models\Mission;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ApproveMissionProposalsController
{
    public function __invoke(
        Request $request,
        ApproveProposalAction $approveProposal,
        Mission $mission,
        Proposal $proposal,
    ): Response {
        /** @var User $customer */
        $customer = $request->user();

        if ($mission->customer_id !== $customer->id) {
            abort(SymfonyResponse::HTTP_FORBIDDEN, 'can_not_approve_others');
        }

        $approveProposal->execute($proposal);

        return response()->noContent();
    }
}
