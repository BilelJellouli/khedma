<?php

declare(strict_types=1);

namespace App\Actions\Agent;

use App\Events\Agent\AgentContactCreated;
use App\Models\Agent;
use App\Models\AgentContact;

class CreateAgentContactAction
{
    public function execute(Agent $agent, array $data): AgentContact
    {
        /** @var AgentContact $agentContact */
        $agentContact = $agent->contacts()->create($data);

        AgentContactCreated::dispatch($agentContact);

        return $agentContact;
    }
}
