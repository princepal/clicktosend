<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Template;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use App\Models\UserPlan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')
                ->scopes([
                    'openid',
                    'profile',
                    'email',
                    'https://www.googleapis.com/auth/gmail.send'
                ])
                ->with(['access_type' => 'offline', 'prompt' => 'consent'])
                ->stateless()
                ->user();

            $refreshToken = $googleUser->refreshToken;
            if (empty($refreshToken)) {
                $existingUser = User::where('email', $googleUser->email)->first();
                if ($existingUser && !empty($existingUser->google_refresh_token)) {
                    $refreshToken = $existingUser->google_refresh_token;
                }
            }
            $smtpUsername = $googleUser->email;
            $smtpPassword = Str::random(16);
            $user = User::updateOrCreate(
                ['email' => $googleUser->email],
                [
                    'name' => $googleUser->name,
                    'google_id' => $googleUser->id,
                    'email_verified_at' => now(),
                    'active' => 1,
                    'role' => 'admin',
                    'password' => bcrypt(Str::random(32)),
                    'verified' => true,
                    'smtp_username' => $smtpUsername,
                    'smtp_password' => Crypt::encryptString($smtpPassword),
                    'google_token' => $googleUser->token,
                    'google_refresh_token' => $refreshToken,
                ]
            );

            // Check if user has any templates, if not create a default template
            if ($user->templates()->count() === 0) {
                Template::create([
                    'user_id' => $user->id,
                    'name' => 'Default',
                    'subject' => 'Load from {-origin-} to {-dest-}',
                    'body' => '<p>Hello, team!<br><br>Can we get more info pls about the load from {-origin-} to {-dest-} which is available at {-avail-} &nbsp;and what is the best rate?</p><p>{-includeinemail-}</p>',
                ]);
            }

            // Check if user has no plan, then add a 3-day trial UserPlan
            if ($user->userplans()->count() === 0) {
                $now = Carbon::now();
                $endDate = Carbon::parse($now)->addDays(3);
                UserPlan::create([
                    'user_id' => $user->id,
                    'start_date' => $now,
                    'end_date' => $endDate,
                    'status' => 'trial',
                ]);
                \Log::info('Trial UserPlan created for user:', [$user->id]);
            }

            Auth::login($user);
            return redirect()->intended(route('dashboard', absolute: false));
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Google login failed. Please try again.']);
        }
    }
}
