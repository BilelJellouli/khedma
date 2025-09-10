<?php

namespace App\Types;

use App\Models\User;

final readonly class LoggedInUser
{
    public function __construct(
        public User $user,
        public string $plainTextAccessToken
    ) {}
}
