<?php

declare(strict_types=1);

namespace App\Http\Controllers\Authentication;

use App\Actions\Authentication\LoginUserAction;
use App\Actions\Authentication\RegisterUserAction;
use App\Enums\UserRole;
use App\Http\Requests\Authentication\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Types\LoggedInUser;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class RegisterUsersController
{
    public function __invoke(
        RegisterUserAction $registerUser,
        LoginUserAction $loginUser,
        RegisterUserRequest $request,
        UserRole $userRole,
    ): JsonResponse {
        $user = $registerUser->execute($userRole, $request->validated());

        /** @var LoggedInUser $loggedInUser */
        $loggedInUser = $loginUser->apiLogin('mobile')
            ->execute($user->email, $request->input('password'));

        return response()->json([
            'user' => UserResource::make($loggedInUser->user),
            'accessToken' => $loggedInUser->plainTextAccessToken,
        ], Response::HTTP_CREATED);
    }
}
