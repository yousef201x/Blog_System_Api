<?php

namespace App\Http\Controllers\Api\Users\Auth;

use App\Http\Controllers\Controller;
use App\Services\User\UserAuthService;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    use UserAuthService;

    public function logout(Request $request)
    {

        // Retrieve the token from the authenticated user
        $token = $request->user()->currentAccessToken();

        // Delete the token
        $token->delete();

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ]);
    }

    public function destroyAllSessions(Request $request)
    {
        // Revoke all tokens for the authenticated user
        $request->user()->tokens()->delete();

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Logged out from all devices successfully.',
        ]);
    }
}
