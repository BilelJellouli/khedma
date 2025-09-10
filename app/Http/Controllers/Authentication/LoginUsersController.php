<?php

namespace App\Http\Controllers\Authentication;

use App\Actions\Authentication\LoginUserAction;
use App\Http\Requests\Authentication\LoginUserRequest;
use App\Http\Resources\UserResource;
use App\Types\LoggedInUser;
use Illuminate\Http\JsonResponse;

class LoginUsersController
{
    public function __invoke(
        LoginUserAction $loginUser,
        LoginUserRequest $request,
    ): JsonResponse {

        [ 'email' => $email, 'password' => $password ] = $request->validated();

        /** @var LoggedInUser $loggedInUser */
        $loggedInUser = $loginUser->apiLogin('mobile')->execute($email, $password);

        return response()->json([
            'user' => UserResource::make($loggedInUser->user),
            'accessToken' => $loggedInUser->plainTextAccessToken,
        ]);
    }
}
