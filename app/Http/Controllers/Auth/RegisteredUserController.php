<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Template;
use App\Models\UserPlan;
use Carbon\Carbon;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,superadmin'],
        ]);

        // Generate OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'otp' => $otp,
            'verified' => false,
        ]);

        if ($user->templates()->count() === 0) {
            Template::create([
                'user_id' => $user->id,
                'name' => 'Default',
                'subject' => 'Load from {-origin-} to {-dest-}',
                'body' => '<p>Hello, team!<br><br>Can we get more info pls about the load from {-origin-} to {-dest-} which is available at {-avail-} &nbsp;and what is the best rate?</p><p>{-includeinemail-}</p>',
            ]);
            \Log::info('Default template created for user:', [$user->id]);
        }

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

        event(new Registered($user));

        // Send OTP email
        $this->sendOtpEmail($user, $otp);

        return redirect()->route('otp.show', ['email' => $user->email])
            ->with('status', 'Registration successful! Please check your email for OTP verification.');
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
