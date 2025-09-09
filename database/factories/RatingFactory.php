<?php

namespace Database\Factories;

use App\Models\Agent;
use App\Models\Mission;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
class RatingFactory extends Factory
{
    protected $model = Rating::class;

    public function definition(): array
    {
        return [
            'mission_id' => Mission::factory(),
            'agent_id' => Agent::factory(),
            'customer_id' => User::factory()->customer(),
            'rate' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->text(),
        ];
    }
}
