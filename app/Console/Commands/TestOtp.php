<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestOtp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:otp {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test OTP functionality for a given email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        // Find or create a test user
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found!");
            return 1;
        }
        
        // Generate OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Update user with OTP
        $user->update([
            'otp' => $otp,
            'verified' => false
        ]);
        
        $this->info("Generated OTP for {$email}: {$otp}");
        
        // Try to send email
        try {
            Mail::send('emails.otp', [
                'user' => $user,
                'otp' => $otp
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Test OTP for Click To Send');
            });
            
            $this->info("OTP email sent successfully! Check the logs at storage/logs/laravel.log");
        } catch (\Exception $e) {
            $this->error("Failed to send email: " . $e->getMessage());
        }
        
        return 0;
    }
}
