<?php

declare(strict_types=1);

namespace App\Actions\Agent;

use App\Events\Agent\AgentCreated;
use App\Models\Agent;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class CreateAgentAction
{
    public function execute(
        User $user,
        Collection $services,
        array $data
    ): Agent {
        $agent = new Agent($data);

        $agent->user()->associate($user);

        $agent->save();

        $agent->services()->sync($services->pluck('id'));

        AgentCreated::dispatch($agent);

        return $agent;
    }
}
