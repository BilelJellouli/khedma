<?php

namespace App\Actions\User;

use App\Enums\UserRole;
use App\Events\Users\CustomerUserCreated;
use App\Models\User;

class CreateCustomerUserAction extends CreateUserAction
{
    function getUserRole(): UserRole
    {
        return UserRole::CUSTOMER;
    }

    public function getCreatedUserForRoleEvent(User $user): CustomerUserCreated
    {
        return new CustomerUserCreated($user);
    }
}
