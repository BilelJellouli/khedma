<?php

namespace App\Actions\User;

use App\Enums\UserRole;
use App\Events\Users\AgentUserCreated;
use App\Models\User;

class CreateAgentUserAction extends CreateUserAction
{
    public readonly UserRole $userRole;

    public function __construct()
    {
        $this->userRole = UserRole::AGENT;
    }

    public function execute(array $data): User
    {
        $user = parent::execute($data);

        AgentUserCreated::dispatch($user);

        return $user;
    }
}
