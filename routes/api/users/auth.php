<?php

use App\Http\Controllers\Api\Users\Auth\LoginController;
use App\Http\Controllers\Api\Users\Auth\LogoutController;
use App\Http\Controllers\Api\Users\Auth\RegisterController;
use App\Http\Controllers\Api\Users\Auth\Socialite\SocialiteController;
use App\Http\Middleware\AuthRateLimiter;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;


Route::middleware(AuthRateLimiter::class)->group(function () {
    Route::post("auth/user/register", [RegisterController::class, 'register']);
    Route::post("auth/user/login", [LoginController::class, 'login']);

    Route::middleware("auth:sanctum")->group(function () {
        Route::post("auth/user/logout", [LogoutController::class, 'logout']);
        Route::post("auth/user/logout/all", [LogoutController::class, 'destroyAllSessions']);
    });

    Route::middleware([StartSession::class])->group(function () {
        Route::get('auth/user/oauth/{provider}/callback', [SocialiteController::class, 'callback']);
        Route::get('auth/user/oauth/{provider}/redirect', [SocialiteController::class, 'redirect']);
    });
});
