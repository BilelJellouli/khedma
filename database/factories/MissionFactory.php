<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\MissionStatus;
use App\Enums\MissionType;
use App\Models\Mission;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MissionFactory extends Factory
{
    protected $model = Mission::class;

    public function definition(): array
    {
        return [
            'customer_id' => User::factory()->customer(),
            'service_id' => Service::factory(),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'location' => $this->faker->address(),
            'status' => $this->faker->randomElement(MissionStatus::cases()),
            'type' => $this->faker->randomElement(MissionType::cases()),
            'budget' => $this->faker->numberBetween(1000, 10000),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes): array => ['status' => MissionStatus::PENDING]);
    }

    public function live(): static
    {
        return $this->state(fn (array $attributes): array => ['status' => MissionStatus::LIVE]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes): array => ['status' => MissionStatus::CANCELLED]);
    }

    public function oneTime(): static
    {
        return $this->state(fn (array $attributes): array => ['type' => MissionType::ONE_TIME]);
    }

    public function repetitive(): static
    {
        return $this->state(fn (array $attributes): array => ['type' => MissionType::REPETITIVE]);
    }

    public function partTime(): static
    {
        return $this->state(fn (array $attributes): array => ['type' => MissionType::PART_TIME]);
    }

    public function fullTime(): static
    {
        return $this->state(fn (array $attributes): array => ['type' => MissionType::FULL_TIME]);
    }
}
