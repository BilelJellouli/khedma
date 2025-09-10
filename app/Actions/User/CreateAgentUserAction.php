<?php

namespace App\Actions\User;

use App\Enums\UserRole;
use App\Events\Users\AgentUserCreated;
use App\Models\User;

class CreateAgentUserAction extends CreateUserAction
{
    function getUserRole(): UserRole
    {
        return UserRole::AGENT;
    }

    function getCreatedUserForRoleEvent(User $user): AgentUserCreated
    {
        return new AgentUserCreated($user);
    }
}
