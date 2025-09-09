<?php

namespace Database\Factories;

use App\Enums\ProposalInitiator;
use App\Models\Agent;
use App\Models\Mission;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProposalFactory extends Factory
{
    protected $model = Proposal::class;

    public function definition(): array
    {
        return [
            'mission_id' => Mission::factory(),
            'customer_id' => User::factory()->customer(),
            'agent_id' => Agent::factory(),
            'initiator' => $this->faker->randomElement(ProposalInitiator::cases())
        ];
    }

    public function initiatedByCustomer(): static
    {
        return $this->state(fn (array $attributes) => ['initiator' => ProposalInitiator::CUSTOMER]);
    }

    public function initiatedByAgent(): static
    {
        return $this->state(fn (array $attributes) => ['initiator' => ProposalInitiator::AGENT]);
    }

    public function initiatedBySystem(): static
    {
        return $this->state(fn (array $attributes) => ['initiator' => ProposalInitiator::SYSTEM]);
    }
}
