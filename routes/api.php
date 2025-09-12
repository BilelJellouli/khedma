<?php

declare(strict_types=1);

use App\Http\Controllers\Authentication\LoginUsersController;
use App\Http\Controllers\Authentication\LogoutUsersController;
use App\Http\Controllers\Authentication\RegisterUsersController;
use App\Http\Controllers\Mission\DeleteMissionsController;
use App\Http\Controllers\Mission\ShowMissionsController;
use App\Http\Controllers\Mission\StoreMissionsController;
use App\Http\Controllers\Mission\UpdateMissionsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'guest'])->group(function (): void {
    Route::post('auth/{userRole}/register', RegisterUsersController::class)->name('auth.register');
    Route::post('auth/login', LoginUsersController::class)->name('auth.login');
});

Route::middleware(['api', 'auth:sanctum'])->group(function (): void {
    Route::get('/user', fn (Request $request) => $request->user());
    Route::post('auth/logout', LogoutUsersController::class)->name('auth.logout');

    Route::prefix('missions')->group(function (): void {
        Route::post('/', StoreMissionsController::class)->name('missions.store');
        Route::get('{mission}', ShowMissionsController::class)->name('missions.show');
        Route::put('{mission}', UpdateMissionsController::class)->name('missions.update');
        Route::delete('{mission}', DeleteMissionsController::class)->name('missions.delete');
    });
});
