<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class MicrosoftController extends Controller
{
    /**
     * Redirect the user to the Microsoft authentication page.
     */
    public function redirectToMicrosoft(): RedirectResponse
    {
        return Socialite::driver('microsoft')->redirect();
    }

    /**
     * Obtain the user information from Microsoft.
     */
    public function handleMicrosoftCallback(): RedirectResponse
    {
        try {
            $microsoftUser = Socialite::driver('microsoft')->user();
            $user = User::updateOrCreate([
                'email' => $microsoftUser->email,
            ], [
                'name' => $microsoftUser->name ?? $microsoftUser->nickname ?? $microsoftUser->email,
                'microsoft_id' => $microsoftUser->id,
                'email_verified_at' => now(),
                'active' => 1,
                'role' => 'admin',
                'password' => Str::random(32),
                'verified' => true, // Microsoft users are already verified
            ]);
            Auth::login($user);
            return redirect()->intended(route('dashboard', absolute: false));
        } catch (\Exception $e) {
            \Log::error('Microsoft login error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors(['email' => 'Microsoft login failed. Please try again.']);
        }
    }
} 