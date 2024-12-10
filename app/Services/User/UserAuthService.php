<?php

namespace App\Services\User;

use App\Models\Session;
use App\Models\User;
use App\Notifications\NewLoginAlert;
use App\Notifications\UserRegisteredNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

trait UserAuthService
{
    function generateToken(User $user)
    {
        return $user->createToken('Api Token')->plainTextToken;
    }

    function insertSessionInfo(Request $request, User $user, $token)
    {
        $session = new Session;
        $session->id = session()->getId();
        $session->user_id = $user->id;
        $session->valid_token = $token;
        $session->ip_address = $request->ip();
        $session->user_agent = $request->userAgent();
        $session->last_activity = Carbon::now()->format('Y-m-d H:i:s');
        $session->save();
        return $session;
    }

    function destroyAllSessions($userId)
    {
        $deleted = DB::table('sessions')->where('user_id', $userId)->delete();
    }

    function welcomeNotification(User $user)
    {
        $user->notify(new UserRegisteredNotification($user->name));
    }

    function newLoginAlert(Request $request, User $user)
    {
        $loginDetails = [
            'ip_address' => $request->ip(),
            'device' => $request->header('User-Agent'),
            'time' => now()->toDateTimeString(),
        ];

        $user->notify(new NewLoginAlert($loginDetails));
    }
}
