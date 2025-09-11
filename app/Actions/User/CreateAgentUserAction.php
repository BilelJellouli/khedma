<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Enums\UserRole;
use App\Events\User\AgentUserCreated;
use App\Models\User;

class CreateAgentUserAction extends CreateUserAction
{
    protected UserRole $userRole = UserRole::AGENT;

    public function execute(array $data): User
    {
        $user = parent::execute($data);

        AgentUserCreated::dispatch($user);

        return $user;
    }
}
