<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthRateLimiter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Define a unique key for rate limiting based on the user's IP or other identifying information
        $key = $this->resolveKey($request);

        // Rate limit: Allow 5 login attempts per 20 minutes per IP or user
        $maxAttempts = 5;
        $decayMinutes = 3;

        // Check if the user has exceeded the rate limit
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $retryAfter = RateLimiter::availableIn($key); // Time in seconds until the limit resets
            return response()->json([
                'message' => 'Too many login attempts. Please try again later.',
                'retry_after_seconds' => $retryAfter,
            ], 429); // 429 Too Many Requests
        }

        // Increment the attempts
        RateLimiter::hit($key, $decayMinutes * 60);

        return $next($request);
    }

    /**
     * Resolve the unique key for rate limiting.
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    protected function resolveKey(Request $request): string
    {
        // Use IP address for rate limiting as an example
        return 'login:' . Str::slug($request->ip());
    }
}
