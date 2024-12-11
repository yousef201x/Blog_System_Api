<?php

namespace App\Http\Controllers\Api\Users\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Validation\UserValidationRules;
use Illuminate\Support\Facades\Hash;
use App\Services\User\UserAuthService;

class RegisterController extends Controller
{
    use UserValidationRules;
    use UserAuthService;

    public function register(Request $request)
    {

        // Validate Request data
        $request->validate($this->registerRules());

        // Insert new Record
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Generate auth token
        $token = $this->setAuthToken($user);

        // insert new session
        $session = $this->insertSessionInfo($request, $user, $token);

        // send welcome notification
        $this->welcomeNotification($user);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ], 201);
    }
}
