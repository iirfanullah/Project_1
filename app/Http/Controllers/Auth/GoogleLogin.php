<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class GoogleLogin extends Controller
{
    public function loginPage()
    {
        return Socialite::driver('google')
            ->scopes(['https://www.googleapis.com/auth/calendar', 'https://www.googleapis.com/auth/calendar.events'])
            ->with(['access_type' => 'offline', 'prompt' => 'consent'])
            ->redirect();
    }
    public function googleRedirect(Request $request)
    {
        try {
            if (auth()->check()) {
                $user = Socialite::driver('google')->stateless()->user();
                $loggedInUser = auth()->user();
                $loggedInUser->google_id = $user->id;
                $loggedInUser->google_access_token = $user->token;
                $loggedInUser->google_refresh_token = $user->refreshToken;
                $loggedInUser->save();
                Session::put('access_token', $user->token);
                Session::flash('status', 'Google Account Connect Successful');
                return to_route('home');
            }
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Google authentication failed');
        }
    }
}
