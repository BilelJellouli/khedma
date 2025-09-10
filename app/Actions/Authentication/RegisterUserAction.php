<?php

declare(strict_types=1);

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
    ) {}

    public function execute(UserRole $userRole, array $data): User
    {
        $user = match (true) {
            $userRole === UserRole::AGENT => $this->createAgentUser->execute($data),
            $userRole === UserRole::CUSTOMER => $this->createCustomerUser->execute($data),
            default => throw new \InvalidArgumentException('Invalid user role')
        };

        UserRegistered::dispatch($user);

        return $user;
    }
}
