<?php

declare(strict_types=1);

namespace App\Actions\Proposal;

use App\Enums\ProposalStatus;
use App\Events\Proposal\ProposalRejected;
use App\Models\Proposal;

class RejectProposalAction
{
    public function __construct(protected UpdateProposalAction $updateProposal) {}

    public function execute(Proposal $proposal, array $data): Proposal
    {
        $this->updateProposal->execute($proposal, [
            ...$data,
            'status' => ProposalStatus::REJECTED,
        ]);

        ProposalRejected::dispatch($proposal);

        return $proposal;
    }
}
