<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Agent;
use App\Models\Recommendation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecommendationFactory extends Factory
{
    protected $model = Recommendation::class;

    public function definition(): array
    {
        return [
            'agent_id' => Agent::factory(),
            'customer_id' => User::factory()->customer(),
            'relation' => $this->faker->sentence(),
            'comment' => $this->faker->text(),
        ];
    }
}
