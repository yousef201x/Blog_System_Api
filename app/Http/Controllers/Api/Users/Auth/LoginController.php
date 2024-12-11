<?php

namespace App\Http\Controllers\Api\Users\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\User\UserAuthService;
use App\Validation\UserValidationRules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    use UserValidationRules;
    use UserAuthService;

    public function login(Request $request)
    {
        // Validate the login credentials
        $request->validate($this->loginRules());

        // Attempt to find the user by email
        $user = User::where('email', $request->email)->first();

        // Validate user existence and password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Invalid credentials or user not found.',
                ],
                401
            );
        }

        // Generate a token for the authenticated user
        $token = $this->setAuthToken($user);

        $this->insertSessionInfo($request, $user, $token);

        // Retrieve all sessions associated with the user (using ORM)
        $sessions = $user->sessions()
            ->get(['id', 'ip_address', 'user_agent', 'last_activity']);

        $this->newLoginAlert($request, $user);

        // Return the authenticated user details along with sessions
        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ]);
    }
}
