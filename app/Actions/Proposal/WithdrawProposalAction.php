<?php

declare(strict_types=1);

namespace App\Actions\Proposal;

use App\Enums\ProposalStatus;
use App\Events\Proposal\ProposalWithdrawn;
use App\Models\Proposal;

class WithdrawProposalAction
{
    public function __construct(protected UpdateProposalAction $updateProposal) {}

    public function execute(Proposal $proposal): Proposal
    {
        $this->updateProposal->execute($proposal, [
            'status' => ProposalStatus::WITHDRAW,
        ]);

        ProposalWithdrawn::dispatch($proposal);

        return $proposal;
    }
}
