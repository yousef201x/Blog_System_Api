<?php

namespace App\Services\User;

use App\Models\Session;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

trait UserRegisterService
{
    function generateToken(User $user)
    {
        return $user->createToken('Api Token')->plainTextToken;
    }

    function insertSessionInfo(Request $request, User $user)
    {
        $session = new Session;
        $session->id = session()->getId();
        $session->user_id = $user->id;
        $session->ip_address = $request->ip();
        $session->user_agent = $request->userAgent();
        $session->last_activity = Carbon::now()->format('Y-m-d H:i:s');
        $session->save();
        return $session;
    }
}
