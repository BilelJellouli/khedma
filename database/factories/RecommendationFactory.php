<?php

namespace Database\Factories;

use App\Models\Agent;
use App\Models\Mission;
use App\Models\Recommendation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecommendationFactory extends Factory
{
    protected $model = Recommendation::class;

    public function definition(): array
    {
        return [
            'mission_id' => Mission::factory(),
            'agent_id' => Agent::factory(),
            'customer_id' => User::factory()->customer(),
            'relation' => $this->faker->sentence(),
            'comment' => $this->faker->text(),
        ];
    }
}
