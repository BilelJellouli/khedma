<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ProposalInitiator;
use App\Enums\ProposalStatus;
use App\Models\Agent;
use App\Models\Mission;
use App\Models\Proposal;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProposalFactory extends Factory
{
    protected $model = Proposal::class;

    public function definition(): array
    {
        return [
            'mission_id' => Mission::factory(),
            'agent_id' => Agent::factory(),
            'initiator' => $this->faker->randomElement(ProposalInitiator::cases()),
            'status' => $this->faker->randomElement(ProposalStatus::cases()),
        ];
    }

    public function initiatedByCustomer(): static
    {
        return $this->state(fn (array $attributes): array => ['initiator' => ProposalInitiator::CUSTOMER]);
    }

    public function initiatedByAgent(): static
    {
        return $this->state(fn (array $attributes): array => ['initiator' => ProposalInitiator::AGENT]);
    }

    public function initiatedBySystem(): static
    {
        return $this->state(fn (array $attributes): array => ['initiator' => ProposalInitiator::SYSTEM]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes): array => ['status' => ProposalStatus::PENDING]);
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes): array => ['status' => ProposalStatus::APPROVED]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes): array => ['status' => ProposalStatus::REJECTED]);
    }
}
