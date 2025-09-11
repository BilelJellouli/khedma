<?php

declare(strict_types=1);

namespace App\Actions\Authentication;

use App\Events\Authentication\UserLoggedIn;
use App\Exceptions\UserBannedException;
use App\Exceptions\UserDoNotExistsException;
use App\Exceptions\WrongCredentialException;
use App\Models\User;
use App\Types\LoggedInUser;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Support\Facades\Hash;

class LoginUserAction
{
    private bool $apiLogin = false;

    private string $device;

    public function __construct(private readonly Factory $auth) {}

    public function apiLogin(string $device): self
    {
        $this->device = $device;
        $this->apiLogin = true;

        return $this;
    }

    public function execute(string $email, string $password): User|LoggedInUser
    {
        $user = User::where('email', $email)->first();

        if (is_null($user)) {
            throw new UserDoNotExistsException;
        }

        if (! Hash::check($password, $user->password)) {
            throw new WrongCredentialException;
        }

        if (! is_null($user->banned_at)) {
            throw new UserBannedException;
        }

        UserLoggedIn::dispatch($user);

        if ($this->apiLogin) {
            return new LoggedInUser($user, $user->createToken($this->device)->plainTextToken);
        }

        $this->auth->guard()->login($user);

        return $user;
    }
}
