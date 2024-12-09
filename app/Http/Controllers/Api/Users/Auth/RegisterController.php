<?php

namespace App\Http\Controllers\Api\Users\Auth;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\User;
use Illuminate\Http\Request;
use App\Validation\UserValidationRules;
use Illuminate\Support\Facades\Hash;
use App\Services\User\UserRegisterService;

class RegisterController extends Controller
{
    use UserValidationRules;
    use UserRegisterService;

    public function register(Request $request)
    {

        $request->validate($this->registerRules());

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $session = $this->insertSessionInfo($request, $user);

        // Generate the token
        $token = $this->generateToken($user);

        return response()->json(['sucess' => 'User registered successfully', 'user' => $user, 'seesions' => $session, 'token' => $token], 201);
    }
}
