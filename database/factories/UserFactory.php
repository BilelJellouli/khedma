<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\UserRole;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password = null;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => $this->faker->randomElement(UserRole::cases()),
            'random_password' => $this->faker->boolean(),
            'facebook_id' => $this->faker->uuid(),
            'google_id' => $this->faker->uuid(),
            'deactivated_at' => null,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes): array => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes): array => ['role' => UserRole::ADMIN]);
    }

    public function customer(): static
    {
        return $this->state(fn (array $attributes): array => ['role' => UserRole::CUSTOMER]);
    }

    public function agent(): static
    {
        return $this->state(fn (array $attributes): array => ['role' => UserRole::AGENT]);
    }

    public function notAdmin(): static
    {
        return $this->state(fn (array $attributes): array => ['role' => $this->faker->randomElement([UserRole::CUSTOMER, UserRole::AGENT])]);
    }

    public function deactivated(): static
    {
        return $this->state(fn (array $attributes): array => ['deactivated_at' => Carbon::now()]);
    }

    public function banned(): static
    {
        return $this->state(fn (array $attributes): array => ['banned_at' => Carbon::now()]);
    }
}
