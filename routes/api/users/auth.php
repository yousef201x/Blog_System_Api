<?php

use App\Http\Controllers\Api\Users\Auth\RegisterController;
use Illuminate\Support\Facades\Route;


Route::post("auth/user/register", [RegisterController::class, 'register']);
