<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\UserPlan;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Services\BamboraService;

class PlanController extends Controller
{
    /**
     * Display a listing of the plans.
     */
    public function index(): View
    {
        // Get user IP address
        //$ip = request()->ip();
        $ip = "24.78.190.90";

        $location = null;
        $country = "";
        try {
            // Use a public geolocation API (ip-api.com)
            $geoResponse = @file_get_contents("http://ip-api.com/json/{$ip}");
            if ($geoResponse !== false) {
                $geoData = json_decode($geoResponse, true);
                if (isset($geoData['status']) && $geoData['status'] === 'success') {
                    $country = $geoData['country'] ?? null;
                    $location = [
                        'country' => $geoData['country'] ?? null,
                        'region' => $geoData['regionName'] ?? null,
                        'city' => $geoData['city'] ?? null,
                        'lat' => $geoData['lat'] ?? null,
                        'lon' => $geoData['lon'] ?? null,
                        'timezone' => $geoData['timezone'] ?? null,
                        'isp' => $geoData['isp'] ?? null,
                    ];
                }
            }
        } catch (\Exception $e) {
            $location = null;
        }

        if (strtolower($country) === "canada") {
            $plans = Plan::where('currency', 'CAD')->orderBy('price', 'asc')->get();
        } else {
            $plans = Plan::where('currency', 'USD')->orderBy('price', 'asc')->get();
        }

        $user = Auth::user();
        $activePlanId = null;
        $activeEndDate = null;
        if ($user) {
            $latestUserPlan = UserPlan::where('user_id', $user->id)
                ->orderBy('bambora_recurring_id', 'desc')
                ->first();
            if ($latestUserPlan) {
                $activePlanId = $latestUserPlan->plan_id;
                $activeEndDate = $latestUserPlan->end_date;
            }
        }
        $planexpired = "";
        $now = Carbon::now();
        //echo $activeEndDate . '<<>>' . $now;
        if ($now > $activeEndDate) {
            $planexpired = "Your plan has expired, Please purchase new plan";
        }
        return view('plans.index', compact('plans', 'activePlanId', 'activeEndDate', 'ip', 'location', 'planexpired'));
    }

    /**
     * Show the form for creating a new plan.
     */
    public function create(): View
    {
        return view('plans.create');
    }

    /**
     * Store a newly created plan in storage.
     */
    public function subscribe(Request $request, Plan $plan)
    {
        $dispatchers = (int) $request->input(
            'dispatchers',
            $request->query('dispatchers', session()->getOldInput('dispatchers', 1))
        );

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to subscribe to a plan.');
        }

        if ($request->isMethod('get')) {
            return view('plans.payment_form', compact('plan', 'dispatchers'));
        }

        // 1. Validate card input
        $data = $request->validate([
            'card_number' => 'required',
            'expiry_month' => 'required',
            'expiry_year' => 'required',
            'cvd' => 'required',
            'name' => 'required',
            'dispatchers' => 'required|integer|min:1',
        ]);

        $dispatchers = (int) $data['dispatchers'];

        $now = Carbon::now();
        $baseStartDate = $now;
        $endDate = $now;
        $baseDurationDays = 0;

        // Determine duration based on plan_id
        switch ($plan->id) {
            case 1:
                $baseDurationDays = 30; // Monthly
                break;
            case 2:
                $baseDurationDays = 365; // Yearly
                break;
            default:
                $baseDurationDays = 30; // Default to monthly
                break;
        }

        $existingPlan = UserPlan::where('user_id', $user->id)
            ->latest('start_date')
            ->first();

        if ($existingPlan && $existingPlan->end_date && Carbon::parse($existingPlan->end_date)->isFuture()) {
            $endDate = Carbon::parse($existingPlan->end_date)->addDays($baseDurationDays);
        }

        $bambora = new BamboraService();
        $customer_code = 'user_' . $user->id;

        try {
            // 2. Check if profile already exists
            $existingProfile = UserPlan::where('user_id', $user->id)
                ->whereNotNull('bambora_profile_id')
                ->latest()
                ->value('bambora_profile_id');

            if ($existingProfile) {
                // 2a. Update card under existing customer_code
                $bambora->updateCardForExistingProfile($existingProfile, [
                    'name' => $data['name'],
                    'number' => $data['card_number'],
                    'expiry_month' => $data['expiry_month'],
                    'expiry_year' => $data['expiry_year'],
                    'cvd' => $data['cvd'],
                ]);
                $profile = ['customer_code' => $existingProfile];
            } else {
                // 2b. Create new payment profile
                $profile = $bambora->createPaymentProfile([
                    'customer_code' => $customer_code,
                    'name' => $data['name'],
                    'card_number' => $data['card_number'],
                    'expiry_month' => $data['expiry_month'],
                    'expiry_year' => $data['expiry_year'],
                    'cvd' => $data['cvd'],
                    'email' => $user->email,
                ]);

                if (empty($profile) || !isset($profile['customer_code'])) {
                    \Log::error('Profile creation failed', $profile);
                    return back()->with('error', 'Failed to create payment profile.');
                }
            }

            // 3. Create recurring billing
            $amount = ($plan->sale_price ?? $plan->price) * $dispatchers;
            $recurring = $bambora->createRecurringBilling(
                $profile['customer_code'],
                $amount,
                strtolower($plan->frequency)
            );

            if (empty($recurring) || !isset($recurring['id'])) {
                \Log::error('Recurring billing failed', $recurring);
                return back()->with('error', 'Failed to set up recurring billing.');
            }

            // Check for an active plan with same plan_id

            // Update or create UserPlan
            if ($existingPlan) {
                $existingPlan->update([
                    'plan_id' => $plan->id,
                    'dispatchers' => $dispatchers,
                    'start_date' => $baseStartDate,
                    'end_date' => $endDate,
                    'status' => 'active',
                    'bambora_profile_id' => $profile['customer_code'],
                    'bambora_recurring_id' => $recurring['id'],
                ]);
            } else {
                UserPlan::create([
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'dispatchers' => $dispatchers,
                    'start_date' => $baseStartDate,
                    'end_date' => $endDate,
                    'status' => 'active',
                    'bambora_profile_id' => $profile['customer_code'],
                    'bambora_recurring_id' => $recurring['id'],
                ]);
            }

            // Insert the transaction
            Transaction::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'dispatchers' => $dispatchers,
                'amount' => $amount,
                'status' => 'success',
                'bambora_profile_id' => $profile['customer_code'],
                'bambora_recurring_id' => $recurring['id'],
                'transaction_reference' => $recurring['id'] ?? null,
                'response' => json_encode($recurring),
            ]);

            return redirect()->route('plans.index')->with(
                'success',
                'You have successfully subscribed to the ' . $plan->frequency . ' plan for ' . $dispatchers . ' dispatcher(s)!'
            );
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if (method_exists($e, 'getResponse') && $e->getResponse()) {
                $body = (string) $e->getResponse()->getBody();
                $json = json_decode($body, true);
                if (isset($json['message'])) {
                    $message = $json['message'];
                }
            }
            \Log::error('Bambora error: ' . $message);
            return back()->with('error', 'Else catch: ' . $message)->withInput();
        }
    }

    /*public function subscribe(Request $request, Plan $plan)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to subscribe to a plan.');
        }
        
        if ($request->isMethod('get')) {
            return view('plans.payment_form', compact('plan'));
        }
        // Validate card input
        $data = $request->validate([
            'card_number' => 'required',
            'expiry_month' => 'required',
            'expiry_year' => 'required',
            'cvd' => 'required',
            'name' => 'required',
        ]);

        $bambora = new BamboraService();

        try {
            // 1. Create payment profile
            $profile = $bambora->createPaymentProfile([
                'customer_code' => 'user_' . $user->id,
                'name' => $data['name'],
                'card_number' => $data['card_number'],
                'expiry_month' => $data['expiry_month'],
                'expiry_year' => $data['expiry_year'],
                'cvd' => $data['cvd'],
                'email' => $user->email,
            ]);
            \Log::info('Bambora profile response', $profile);

            if (empty($profile) || !isset($profile['customer_code'])) {
                \Log::error('Profile creation failed', $profile);
                return back()->with('error', 'Failed to create payment profile.');
            }

            // 2. Create recurring billing
            $recurring = $bambora->createRecurringBilling(
                $profile['customer_code'],
                $plan->sale_price ?? $plan->price,
                strtolower($plan->frequency)
            );
            \Log::info('Bambora recurring response', $recurring);

            if (empty($recurring) || !isset($recurring['id'])) {
                \Log::error('Recurring billing failed', $recurring);
                return back()->with('error', 'Failed to set up recurring billing.');
            }

            // 3. Save user plan
            UserPlan::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'start_date' => Carbon::now(),
                'end_date' => null,
                'status' => 'active',
                'bambora_profile_id' => $profile['customer_code'] ?? null,
                'bambora_recurring_id' => $recurring['id'] ?? null,
            ]);

            return redirect()->route('plans.index')->with('success', 'You have successfully subscribed to the plan!');
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if (method_exists($e, 'getResponse') && $e->getResponse()) {
                $body = (string) $e->getResponse()->getBody();
                $json = json_decode($body, true);
                if (isset($json['message'])) {
                    $message = $json['message'];
                }
            }
            \Log::error('Bambora error: ' . $message);
            return back()->with('error', $message)->withInput();
        }
    }*/
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plan_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'frequency' => 'required|string|max:255',
            'plan_id' => 'required|string|max:255|unique:plans',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        Plan::create($validated);

        return redirect()->route('plans.index')
            ->with('success', 'Plan created successfully.');
    }

    /**
     * Display the specified plan.
     */
    public function show(Plan $plan): View
    {
        return view('plans.show', compact('plan'));
    }

    /**
     * Show the form for editing the specified plan.
     */
    public function edit(Plan $plan): View
    {
        return view('plans.edit', compact('plan'));
    }

    /**
     * Update the specified plan in storage.
     */
    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'plan_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'frequency' => 'required|string|max:255',
            'plan_id' => 'required|string|max:255|unique:plans,plan_id,' . $plan->id,
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $plan->update($validated);

        return redirect()->route('plans.index')
            ->with('success', 'Plan updated successfully.');
    }

    /**
     * Remove the specified plan from storage.
     */
    public function destroy(Plan $plan)
    {
        $plan->delete();

        return redirect()->route('plans.index')
            ->with('success', 'Plan deleted successfully.');
    }

    /**
     * Subscribe the authenticated user to a plan (simulate Bambora recurring payment).
     */
    /*public function subscribe(Request $request, Plan $plan)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'You must be logged in to subscribe to a plan.');
            }
            
            // If GET, show payment form
            if ($request->isMethod('get')) {
                return view('plans.payment_form', compact('plan'));
            }
            
            // Validate card input with more specific rules
            $data = $request->validate([
                'card_number' => 'required|string|min:13|max:19',
                'expiry_month' => 'required|string|regex:/^[0-9]{1,2}$/',
                'expiry_year' => 'required|string|regex:/^[0-9]{4}$/',
                'cvd' => 'required|string|min:3|max:4',
                'name' => 'required|string|max:255',
            ], [
                'card_number.required' => 'Card number is required.',
                'card_number.min' => 'Card number must be at least 13 digits.',
                'card_number.max' => 'Card number cannot exceed 19 digits.',
                'expiry_month.required' => 'Expiry month is required.',
                'expiry_month.regex' => 'Expiry month must be 1-2 digits (1-12).',
                'expiry_year.required' => 'Expiry year is required.',
                'expiry_year.regex' => 'Expiry year must be 4 digits.',
                'cvd.required' => 'CVD/CVV is required.',
                'cvd.min' => 'CVD/CVV must be at least 3 digits.',
                'cvd.max' => 'CVD/CVV cannot exceed 4 digits.',
                'name.required' => 'Cardholder name is required.',
            ]);

            // Additional validation for expiry date
            $expiryMonth = (int)$data['expiry_month'];
            $expiryYear = (int)$data['expiry_year'];
            $currentYear = (int)date('Y');
            $currentMonth = (int)date('n');

            if ($expiryMonth < 1 || $expiryMonth > 12) {
                return back()->withErrors(['expiry_month' => 'Expiry month must be between 1 and 12.'])->withInput();
            }

            if ($expiryYear < $currentYear || ($expiryYear === $currentYear && $expiryMonth < $currentMonth)) {
                return back()->withErrors(['expiry_year' => 'Card has expired.'])->withInput();
            }

            // Check if user already has an active subscription to this plan
            $existingSubscription = UserPlan::where('user_id', $user->id)
                ->where('plan_id', $plan->id)
                ->where('status', 'active')
                ->first();

            if ($existingSubscription) {
                return back()->with('error', 'You already have an active subscription to this plan.');
            }

            $bambora = new BamboraService();

            // Use database transaction to ensure data consistency
            \DB::beginTransaction();

            try {
                // 1. Create payment profile
                $profile = $bambora->createPaymentProfile([
                    'customer_code' => 'user_' . $user->id,
                    'name' => $data['name'],
                    'card_number' => $data['card_number'],
                    'expiry_month' => (int)$data['expiry_month'],
                    'expiry_year' => (int)$data['expiry_year'],
                    'cvd' => $data['cvd'],
                    'email' => $user->email,
                ]);

                if (empty($profile) || !isset($profile['customer_code'])) {
                    throw new \Exception('Failed to create payment profile. Please check your card details and try again.');
                }

                // 2. Create recurring billing
                $recurring = $bambora->createRecurringBilling(
                    $profile['customer_code'],
                    $plan->sale_price ?? $plan->price,
                    strtolower($plan->frequency) // e.g., 'monthly'
                );

                if (empty($recurring) || !isset($recurring['id'])) {
                    throw new \Exception('Failed to set up recurring billing. Please try again or contact support.');
                }

                // 3. Save user plan
                $userPlan = UserPlan::create([
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'start_date' => Carbon::now(),
                    'end_date' => null,
                    'status' => 'active',
                    'bambora_profile_id' => $profile['customer_code']['id'] ?? null,
                    'bambora_recurring_id' => $recurring['id'] ?? null,
                ]);

                if (!$userPlan) {
                    throw new \Exception('Failed to save subscription details. Please try again.');
                }

                \DB::commit();

                return redirect()->route('plans.index')
                    ->with('success', 'You have successfully subscribed to the ' . $plan->plan_name . ' plan!');

            } catch (\Exception $e) {
                \DB::rollBack();
                
                // Log the error for debugging
                \Log::error('Subscription error for user ' . $user->id . ': ' . $e->getMessage(), [
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                return back()->with('error', $e->getMessage());
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
            
        } catch (\Exception $e) {
            // Log unexpected errors
            \Log::error('Unexpected error in subscription process: ' . $e->getMessage(), [
                'user_id' => $user->id ?? 'unknown',
                'plan_id' => $plan->id ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'An unexpected error occurred. Please try again or contact support if the problem persists.');
        }
    }*/
}
