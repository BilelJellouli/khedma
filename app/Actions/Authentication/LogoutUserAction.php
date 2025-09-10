<?php

namespace App\Actions\Authentication;

use App\Events\Authentication\UserLoggedOut;
use App\Models\User;
use Illuminate\Contracts\Auth\Factory;

class LogoutUserAction
{
    public function __construct(protected Factory $auth)
    {
    }

    public function execute(User $user): void
    {
        $this->auth->guard()->logout();
        $user->tokens()->delete();
        UserLoggedOut::dispatch($user);
    }
}
