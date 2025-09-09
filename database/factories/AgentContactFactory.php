<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AgentContactType;
use App\Models\AgentContact;
use Illuminate\Database\Eloquent\Factories\Factory;

class AgentContactFactory extends Factory
{
    protected $model = AgentContact::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(AgentContactType::cases()),
            'value' => $this->faker->word(),
            'is_primary' => $this->faker->boolean(),
        ];
    }

    public function facebook(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => AgentContactType::FACEBOOK,
            'value' => $this->faker->url(),
        ]);
    }

    public function phone(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => AgentContactType::PHONE,
            'value' => $this->faker->phoneNumber(),
        ]);
    }

    public function whatsapp(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => AgentContactType::WHATSAPP,
            'value' => $this->faker->phoneNumber(),
        ]);
    }

    public function email(): static
    {
        return $this->state(fn (array $attributes): array => [
            'type' => AgentContactType::EMAIL,
            'value' => $this->faker->email(),
        ]);
    }

    public function primaryContact(): static
    {
        return $this->state(fn (array $attributes): array => ['is_primary' => true]);
    }

    public function notPrimary(): static
    {
        return $this->state(fn (array $attributes): array => ['is_primary' => false]);
    }
}
