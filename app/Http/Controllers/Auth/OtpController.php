<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;


use App\Models\Template;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use App\Models\UserPlan;
use Carbon\Carbon;

class OtpController extends Controller
{
    /**
     * Display the OTP verification view.
     */
    public function show(): View
    {
        return view('auth.otp');
    }

    /**
     * Verify the OTP and login user.
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $user = User::where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$user) {
            return back()->withErrors([
                'otp' => 'The OTP is invalid.',
            ]);
        }

        // Update user as verified and clear OTP
        $user->update([
            'verified' => true,
            'otp' => null,
        ]);

        // Check if user has any templates, if not create a default template
        if ($user->templates()->count() === 0) {
            Template::create([
                'user_id' => $user->id,
                'name' => 'Default',
                'subject' => 'Load from {{origin}} to {{dest}}',
                'body' => '<p>Hello, team!<br><br>Can we get more info pls about the load from {{origin}} to {{dest}} which is available at {{avail}} &nbsp;and what is the best rate?</p>',
            ]);
            \Log::info('Default template created for user:', [$user->id]);
        }

        // Check if user has no plan, then add a 14-day trial UserPlan
        if ($user->userplans()->count() === 0) {
            $now = Carbon::now();
            $endDate = Carbon::parse($now)->addDays(14);
            UserPlan::create([
                'user_id' => $user->id,
                'start_date' => $now,
                'end_date' => $endDate,
                'status' => 'trial',
            ]);
            \Log::info('Trial UserPlan created for user:', [$user->id]);
        }

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    /**
     * Resend OTP to user's email.
     */
    public function resend(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)
            ->where('verified', false)
            ->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'User not found or already verified.',
            ]);
        }

        // Generate new OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->update(['otp' => $otp]);

        // Send OTP email
        $this->sendOtpEmail($user, $otp);

        return back()->with('status', 'OTP has been resent to your email.');
    }

    /**
     * Send OTP email to user.
     */
    private function sendOtpEmail(User $user, string $otp): void
    {
        Mail::send('emails.otp', [
            'user' => $user,
            'otp' => $otp
        ], function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Your OTP for Click To Send Registration');
        });
    }
}
