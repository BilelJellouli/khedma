<?php

declare(strict_types=1);

namespace App\Http\Controllers\Authentication;

use App\Actions\Authentication\LogoutUserAction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LogoutUsersController
{
    public function __invoke(
        Request $request,
        LogoutUserAction $logoutUser
    ): Response {
        /** @var User $user */
        $user = $request->user();

        $logoutUser->execute($user);

        return response()->noContent();
    }
}
