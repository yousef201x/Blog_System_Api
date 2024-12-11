<?php

namespace App\Http\Controllers\Api\Users\Auth\Socialite;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use App\Services\User\UserAuthService;

class SocialiteController extends Controller
{
    use UserAuthService;

    private function login($email, $provider, Request $request)
    {
        // Check if the user already exists by email and provider
        $user = User::where('email', $email)
            ->where('provider', $provider)
            ->first();

        if (!$user) {
            return null; // User not found
        }

        // Generate a token for the user
        $token = $this->setAuthToken($user);
        $this->newLoginAlert($request, $user);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    private function register($name, $email, $provider)
    {
        // Register the user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make(Str::uuid()), // Generate and hash a unique password
            'provider' => $provider, // Save the provider for identification
        ]);

        // Generate a token for the user
        $token = $this->setAuthToken($user);
        $this->welcomeNotification($user);
        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function redirect(Request $request)
    {

        // Get the provider from the request
        $provider = $request->provider;

        // Retrieve user details from the provider
        $socialUser = Socialite::driver($provider)->user();

        // Extract details from the social provider response
        $name = $socialUser->getName() ?? 'Unknown User';
        $email = $socialUser->getEmail();

        // Attempt login
        $loginResponse = $this->login($email, $provider, $request);

        if (!$loginResponse) {
            // If login fails, register the user
            $loginResponse = $this->register($name, $email, $provider);
        }

        // Return a response with the user and token
        return response()->json([
            'success' => true,
            'data' => $loginResponse,
        ]);
    }

    public function callback($provider)
    {
        // Redirect the user to the social provider's authentication page
        return Socialite::driver($provider)->redirect();
    }
}
