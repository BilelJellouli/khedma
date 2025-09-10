<?php

namespace App\Actions\User;

use App\Enums\UserRole;
use App\Events\Users\UserCreated;
use App\Models\User;
use Illuminate\Support\Str;

abstract class CreateUserAction
{
    public readonly UserRole $userRole;
    public function execute(array $data): User
    {
        $randomPassword = Str::random(8);

        $data = [
            ...$data,
            'password' => bcrypt($data['password'] ?? $randomPassword),
            'random_password' => !isset($data['password']),
            'role' => $this->userRole,
        ];

        $user = new User($data);

        $user->role = $this->userRole;

        $user->save();

        UserCreated::dispatch($user);

        return $user;
    }
}
