<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Models\Template;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\UserPlan;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;


class UserApiController extends Controller
{
    /**
     * Get user data by email (API).
     */
    public function getByEmail(Request $request): JsonResponse
    {

        $data = $request->input('userdata');

        $name = $data['name'] ?? null;
        $email = $data['email'] ?? null;
        $googleId = $data['google_id'] ?? null;
        //$token = $data['token'] ?? null;

        if (!$email) {
            return response()->json(['error' => 'Email is required'], 422);
        }

        $user = User::where('email', $email)
            ->with([
                'userplans.plan',
                'templates',
                'loadboards' // eager load loadboards
            ])
            ->first();

        if (!$user) {
            $user = User::updateOrCreate([
                'email' => $email,
            ], [
                'name' => $name,
                'google_id' => $googleId,
                'email_verified_at' => now(),
                'active' => 1,
                'role' => 'admin',
                'password' => Str::random(32),
                'verified' => true,
            ]);
            \Log::info('User after updateOrCreate:', [$user]);

            $token = JWTAuth::fromUser($user);
            User::where('id', $user->id)->update([
                'auth_token' => $token,
            ]);

            // Check if user has any templates, if not create a default template
            if ($user->templates()->count() === 0) {
                Template::create([
                    'user_id' => $user->id,
                    'name' => 'Default',
                    'subject' => 'Load from {-origin-} to {-dest-}',
                    'body' => '<p>Hello, team!<br><br>Can we get more info pls about the load from {-origin-} to {-dest-} which is available at {-avail-} &nbsp;and what is the best rate?</p><p>{-includeinemail-}</p>',
                ]);
                \Log::info('Default template created for user:', [$user->id]);
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

            // Generate JWT token
            $token = JWTAuth::fromUser($user);

            return response()->json([
                'success' => true,
                'message' => 'New User Created',
                'redirect_url' => route('dashboard', absolute: false),
                'user' => $user,
                'error' => 0,
                'token' => $token,
            ], 200);
        } else {
            // Generate JWT token for existing user
            $token = JWTAuth::fromUser($user);
            User::where('id', $user->id)->update([
                'auth_token' => $token,
            ]);
        }


        $userPlan = $user->userplans->first();
        $template = $user->templates->first();
        $loadboards = $user->loadboards;

        $response = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'plan' => $userPlan,
            'template' => $template,
            'loadboards' => $loadboards,
            'error' => 0,
            'redirect_url' => route('dashboard', absolute: false),
            'token' => $token,
        ];

        return response()->json($response);
    }

    /**
     * send email by email id (API)
     */
    public function sendEmail(Request $request): JsonResponse
    {

        $data = $request->input('loaddata');
        $requestdata = array(
            'userEmail' => $data['userEmail'] ?? null,
            'email' => $data['email'] ?? null,
            'Age'  => $data['Age'] ?? null,
            'Rate'  => $data['Rate'] ?? null,
            'Trip'  => $data['Trip'] ?? null,
            'Origin'  => $data['Origin'] ?? null,
            'Dho'  => $data['Dho'] ?? null,
            'Destination'  => $data['Destination'] ?? null,
            'deadheaddhd'  => $data['deadheaddhd'] ?? null,
            'Pickup'  => $data['Pickup'] ?? null,
            'EQ'  => $data['EQ'] ?? null,
            'Length'  => $data['Length'] ?? null,
            'Weight'  => $data['Weight'] ?? null,
            'Capacity'  => $data['Capacity'] ?? null,
            'Company'  => $data['Company'] ?? null,
            'referenceId'  => $data['referenceId'] ?? null,
            'includeinemail' => $data['includeinemail'] ?? null
        );

        $email = $data['userEmail'];
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $template = Template::where('user_id', $user->id)->first();
        if (!$template) {
            return response()->json(['error' => 'Template not found'], 404);
        }
        /*$to = 'atish.think@gmail.com';*/
        $to = "palprince@gmail.com";
        $subject = $this->matchvariable($template->subject, $requestdata);
        $body = $this->matchvariable($template->body, $requestdata);
        if (!$to) {
            return response()->json(['error' => 'Recipient email is required'], 422);
        }
        $mail = new PHPMailer(true);
        /*$gusername = 'prakashchand.think@gmail.com';
        $gpassword = 'qtxfwacgswkwivtt';*/

        $gusername = $user->smtp_username;
        $gpassword = Crypt::decryptString($user->smtp_password);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $gusername;
            $mail->Password   = $gpassword;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->setFrom('info@clicktosend.com', 'Click To Send');
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->send();
            return response()->json(['message' => 'Email sent successfully', 'error' => 0]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo, 'error' => 1], 500);
        }
    }

    public function matchvariable($string, $variables)
    {
        $updatedstring = str_replace('{-origin-}', $variables['Origin'], $string);
        $updatedstring = str_replace('{-dest-}', $variables['Destination'], $updatedstring);
        $updatedstring = str_replace('{-email-}', $variables['email'], $updatedstring);
        $updatedstring = str_replace('{-company-}', $variables['Company'], $updatedstring);
        $updatedstring = str_replace('{-deadhead-}', $variables['deadheaddhd'], $updatedstring);
        $updatedstring = str_replace('{-pickupdate-}', $variables['Pickup'], $updatedstring);
        $updatedstring = str_replace('{-rate-}', $variables['Rate'], $updatedstring);
        $updatedstring = str_replace('{-trip-}', $variables['Trip'], $updatedstring);
        $updatedstring = str_replace('{-weight-}', $variables['Weight'], $updatedstring);
        $updatedstring = str_replace('{-length-}', $variables['Length'], $updatedstring);
        $updatedstring = str_replace('{-avail-}', $variables['Pickup'], $updatedstring);
        $updatedstring = str_replace('{-referencenumber-}', $variables['referenceId'], $updatedstring);
        $updatedstring = str_replace('{-tripdeadhead-}', $variables['Trip'] + $variables['deadheaddhd'], $updatedstring);
        $updatedstring = str_replace('{-includeinemail-}', $variables['includeinemail'], $updatedstring);
        return $updatedstring;
    }

    public function verifyToken(Request $request): JsonResponse
    {
        try {

            $token = $request->bearerToken();

            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token not provided.',
                ], 401);
            }

            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access token.',
                ], 401);
            }

            $isValidToken = User::where('id', $user->id)->where("auth_token", $token)
                ->with([
                    'userplans.plan',
                    'templates',
                    'loadboards'
                ])
                ->first();

            if (!$isValidToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access token.',
                ], 401);
            }

            $userPlan = $isValidToken->userplans->first();
            $template = $isValidToken->templates->first();
            $loadboards = $isValidToken->loadboards;

            $response = [
                'success' => true,
                'message' => 'Token verified successfully.',
                'id' => $isValidToken->id,
                'name' => $isValidToken->name,
                'email' => $isValidToken->email,
                'plan' => $userPlan,
                'template' => $template,
                'loadboards' => $loadboards,
                'error' => 0,
                'redirect_url' => route('dashboard', absolute: false),
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token.',
            ], 401);
        }
    }

    public function userLogin(Request $request): JsonResponse
    {
        $data = $request->input('userdata');
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!$email) {
            return response()->json(['error' => 'Email is required'], 422);
        }

        if (!$password) {
            return response()->json(['error' => 'Password is required'], 422);
        }

        // Find user by email
        $user = User::where('email', $email)->first();

        // Check if user exists and password is valid
        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.',
            ], 401);
        } else {
            $token = JWTAuth::fromUser($user);
            User::where('id', $user->id)->update([
                'auth_token' => $token,
            ]);
        }

        // Optional: generate a token or return user info
        return response()->json([
            'success' => true,
            'user' => $user,
        ]);
    }
}
