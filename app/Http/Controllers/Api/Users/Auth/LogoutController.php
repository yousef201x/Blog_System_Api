<?php

namespace App\Http\Controllers\Api\Users\Auth;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Services\User\UserAuthService;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    use UserAuthService;

    public function logout(Request $request)
    {
        // Ensure the user is authenticated
        $user = $request->user();  // This retrieves the authenticated user

        // Invalidate the user's tokens (assuming you're using multiple tokens)
        $user->tokens()->delete();  // Delete all tokens associated with the user

        // Destroy all user sessions
        $this->destroyAllSessions($user->id);  // Assuming this method deletes sessions for the user

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ]);
    }
}
