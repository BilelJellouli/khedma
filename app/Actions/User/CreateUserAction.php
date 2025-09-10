<?php

namespace App\Actions\User;

use App\Enums\UserRole;
use App\Events\Users\AgentUserCreated;
use App\Events\Users\CustomerUserCreated;
use App\Events\Users\UserCreated;
use App\Models\User;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Str;

abstract class CreateUserAction
{
    protected string $randomPassword;
    public function __construct(
        protected Dispatcher $dispatcher,
    ) {
        $this->randomPassword = Str::random(8);
    }

    abstract function getUserRole(): UserRole;
    abstract function getCreatedUserForRoleEvent(User $user): CustomerUserCreated|AgentUserCreated;
    final public function execute(array $data): User
    {
        $data = [
            ...$data,
            'password' => bcrypt($data['password'] ?? $this->randomPassword),
            'random_password' => !isset($data['password']),
            'role' => $this->getUserRole(),
        ];

        $user = User::create($data);

        UserCreated::dispatch($user);
        $this->dispatcher->dispatch($this->getCreatedUserForRoleEvent($user));

        return $user;
    }
}
