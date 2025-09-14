<?php

declare(strict_types=1);

namespace App\Actions\Proposal;

use App\Events\Proposal\ProposalUpdated;
use App\Models\Proposal;

class UpdateProposalAction
{
    public function execute(Proposal $proposal, array $data): Proposal
    {
        $proposal->update($data);

        ProposalUpdated::dispatch($proposal);

        return $proposal;
    }
}
