<?php

namespace App\Http\Middleware;

use App\Models\Session;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class ValidateSessionIntegrity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->header('Authorization');
        $token = substr($authHeader, 7);

        // Ensure the Authorization header exists
        if (!$authHeader) {
            return response()->json(['error' => 'Authorization token not provided.'], 401);
        }

        // Retrieve the session associated with the token
        $storedSession = Session::select('user_id', 'ip_address', 'user_agent')
            ->where('valid_token', $token)
            ->first();

        $hashedToken = $storedSession->valid_token;

        // Check if a session was found
        if (!$storedSession) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        // Validate IP address and User-Agent
        if ($storedSession->ip_address !== $request->ip() || $storedSession->user_agent !== $request->userAgent() || Hash::check($token, $hashedToken)) {
            return response()->json(['error' => 'Session validation failed.'], 403);
        }

        // Proceed with authenticated actions
        return response()->json([
            'message' => 'Session validated successfully.',
            'user_id' => $storedSession->user_id,
        ]);

        return $next($request);
    }
}
