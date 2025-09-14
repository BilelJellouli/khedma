<?php

declare(strict_types=1);

namespace App\Actions\Proposal;

use App\Enums\ProposalStatus;
use App\Events\Proposal\ProposalApproved;
use App\Models\Proposal;

class ApproveProposalAction
{
    public function __construct(protected UpdateProposalAction $updateProposal) {}

    public function execute(Proposal $proposal): Proposal
    {
        $this->updateProposal->execute($proposal, [
            'status' => ProposalStatus::APPROVED,
        ]);

        ProposalApproved::dispatch($proposal);

        return $proposal;
    }
}
