<?php

namespace App\Actions\Authentication;

use App\Actions\User\CreateAgentUserAction;
use App\Actions\User\CreateCustomerUserAction;
use App\Enums\UserRole;
use App\Events\Authentication\UserRegistered;
use App\Models\User;

class RegisterUserAction
{
    public function __construct(
        protected CreateAgentUserAction $createAgentUser,
        protected CreateCustomerUserAction $createCustomerUser
    ) {
    }

    public function execute(UserRole $userRole, array $data): User
    {
        $user = match ($userRole) {
            UserRole::AGENT => $this->createAgentUser->execute($data),
            UserRole::CUSTOMER => $this->createCustomerUser->execute($data),
        };

        UserRegistered::dispatch($user);

        return $user;
    }
}
