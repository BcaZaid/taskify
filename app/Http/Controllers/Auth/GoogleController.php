<?php

// app/Http/Controllers/Auth/GoogleController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    // Redirect the user to Google's OAuth 2.0 server
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Handle the callback from Google
    public function handleGoogleCallback(Request $request)
    {
        // Get the Google user info
        $googleUser = Socialite::driver('google')->user();

        // Now we can pass the Google user data to the registration process
        return redirect()->route('register')->with('google_user', $googleUser);
    }
}
