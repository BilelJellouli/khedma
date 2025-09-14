<?php

declare(strict_types=1);

namespace App\Actions\Proposal;

use App\Enums\ProposalStatus;
use App\Events\Proposal\ProposalCreated;
use App\Models\Agent;
use App\Models\Mission;
use App\Models\Proposal;

class CreateProposalAction
{
    public function execute(Agent $agent, Mission $mission, array $data): Proposal
    {
        /** @var Proposal $proposal */
        $proposal = $agent->proposals()->make([
            ...$data,
            'status' => ProposalStatus::PENDING,
            'seen_at_by_customer' => null,
            'rejection_reason' => null,
            'rejection_message' => null,
        ]);

        $proposal->mission()->associate($mission);

        $proposal->save();

        ProposalCreated::dispatch($proposal);

        return $proposal;
    }
}
