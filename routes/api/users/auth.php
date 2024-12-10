<?php

use App\Http\Controllers\Api\Users\Auth\LoginController;
use App\Http\Controllers\Api\Users\Auth\LogoutController;
use App\Http\Controllers\Api\Users\Auth\RegisterController;
use App\Http\Middleware\AuthRateLimiter;
use App\Http\Middleware\ValidateSessionIntegrity;
use Illuminate\Support\Facades\Route;


Route::middleware(AuthRateLimiter::class)->group(function () {
    Route::post("auth/user/register", [RegisterController::class, 'register']);
    Route::post("auth/user/login", [LoginController::class, 'login']);
    Route::post("auth/user/logout", [LogoutController::class, 'logout'])->middleware("auth:sanctum");
});
