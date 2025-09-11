<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Enums\UserRole;
use App\Events\Users\CustomerUserCreated;
use App\Models\User;

class CreateCustomerUserAction extends CreateUserAction
{
    protected UserRole $userRole = UserRole::CUSTOMER;

    public function execute(array $data): User
    {
        $user = parent::execute($data);

        CustomerUserCreated::dispatch($user);

        return $user;
    }
}
