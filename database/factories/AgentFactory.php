<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AgentAvailability;
use App\Models\Agent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AgentFactory extends Factory
{
    protected $model = Agent::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->agent(),
            'bio' => $this->faker->text(),
            'experience' => $this->faker->text(),
            'skills' => $this->faker->randomElements(),
            'availability' => $this->faker->randomElement(AgentAvailability::cases()),
            'verified' => $this->faker->boolean(),
            'featured' => $this->faker->boolean(),
        ];
    }

    public function unavailable(): static
    {
        return $this->state(fn (array $attributes): array => ['availability' => AgentAvailability::UNAVAILABLE]);
    }

    public function partTime(): static
    {
        return $this->state(fn (array $attributes): array => ['availability' => AgentAvailability::PART_TIME]);
    }

    public function fullTime(): static
    {
        return $this->state(fn (array $attributes): array => ['availability' => AgentAvailability::FULL_TIME]);
    }

    public function oneTime(): static
    {
        return $this->state(fn (array $attributes): array => ['availability' => AgentAvailability::ONE_TIME]);
    }

    public function verified(): static
    {
        return $this->state(fn (array $attributes): array => ['verified' => true]);
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes): array => ['verified' => false]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes): array => ['featured' => true]);
    }

    public function notFeatured(): static
    {
        return $this->state(fn (array $attributes): array => ['featured' => false]);
    }
}
