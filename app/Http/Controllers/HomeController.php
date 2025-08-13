<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\UserPlan;
use App\Models\User;
use App\Models\Template;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Services\BamboraService;
use Illuminate\Support\Facades\DB;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;


use PHPMailer\PHPMailer\OAuth;
use League\OAuth2\Client\Provider\Google as GoogleProvider;


class HomeController extends Controller
{
    public function index()
    {
        /*$email = "atish.think@gmail.com";
        $user = User::where('email', $email)
            ->with([
                'userplans.plan',
                'templates'
            ])
            ->first();

        $gusername = $user->smtp_username;
        $gpassword = Crypt::decryptString($user->smtp_password);
        // echo $gpassword;
        // die;


        $provider = new GoogleProvider([
            'clientId'     => env('GOOGLE_CLIENT_ID'),
            'clientSecret' => env('GOOGLE_CLIENT_SECRET'),
        ]);

        $mail = new PHPMailer(true);
        $to = 'palprince.think@gmail.com';
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            //$mail->Username   = $gusername;
            //$mail->Password   = $gpassword;
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->AuthType = 'XOAUTH2';
            $mail->setOAuth(new OAuth([
                'provider'      => $provider,
                'clientId'      => env('GOOGLE_CLIENT_ID'),
                'clientSecret'  => env('GOOGLE_CLIENT_SECRET'),
                'refreshToken'  => $user->google_token,
                'userName'      => $user->email,
            ]));

            $mail->setFrom($user->email, 'Click To Send');
            $mail->addAddress($to, 'John');
            $mail->isHTML(true);
            $mail->Subject = "Email send from click to send";
            $mail->Body    = "Here is the body of email";
            $mail->send();
            return response()->json(['message' => 'Email sent successfully', 'error' => 0]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo, 'error' => 1], 500);
        }*/


        // echo "<pre>";
        // print_r($user);
        // die;
        return view('home');
    }
}
