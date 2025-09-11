<?php

declare(strict_types=1);

namespace App\Actions\Authentication;

use App\Events\Authentication\UserLoggedOut;
use App\Models\User;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Auth\StatefulGuard;

class LogoutUserAction
{
    public function __construct(protected Factory $auth) {}

    public function execute(User $user): void
    {
        $guard = $this->auth->guard();

        if ($guard instanceof StatefulGuard) {
            $guard->logout();
        }

        $user->tokens()->delete();
        UserLoggedOut::dispatch($user);
    }
}
