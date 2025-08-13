<nav class="sidebar">
    @php
        $role = auth()->user()->role;
        $user = auth()->user();

        // Get current active plan
        $currentPlan = $user
            ->userplans()
            ->with('plan')
            ->where('status', 'active')
            ->orWhere('status', 'trial')
            ->where('end_date', '>=', now())
            ->orderBy('end_date', 'desc')
            ->first();

        // Calculate days remaining for trial plans
        $daysRemaining = null;
        if ($currentPlan && $currentPlan->status === 'trial' && $currentPlan->end_date) {
            $daysRemaining = round(now()->diffInDays(\Carbon\Carbon::parse($currentPlan->end_date), false));
        }
    @endphp
    <div class="logo-section">
        <div class="d-flex align-items-center">
            <img width="25" height="25" src="{{ asset('build/assets/images/icon.png') }}" alt="Logo">
            <h5 class="mb-0">Click to Send</h5>
        </div>
    </div>
    <div class="mt-3">
        <a href="{{ url('/dashboard') }}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i>
            Dashboard
        </a>
        <a href="{{ url('/templates') }}" class="nav-link {{ Request::is('templates*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text"></i>
            My Templates
        </a>
        {{-- <a href="#" class="nav-link">
            <i class="bi bi-people"></i>
            My Teams
        </a> --}}
    </div>
    <hr class="mx-3">
    <div class="mt-3">
        <a href="{{ url('/plans') }}" class="nav-link {{ Request::is('plans*') ? 'active' : '' }}">
            <i class="bi bi-currency-dollar"></i>
            Billing
        </a>
    </div>

    @if ($currentPlan)
        <div class="trial-card">
            @if ($currentPlan->status === 'trial')
                @if ($daysRemaining > 0)
                    <h6 class="mb-2">Your {{ $currentPlan->plan->plan_name ?? '' }} Trial expires in
                        {{ $daysRemaining }} {{ $daysRemaining === 1 ? 'day' : 'days' }}</h6>
                @else
                    <h6 class="mb-2">Your {{ $currentPlan->plan->plan_name ?? '' }} Trial has expired</h6>
                @endif
            @else
                <h6 class="mb-2">{{ $currentPlan->plan->frequency ?? 'Pro' }} Plan</h6>
                <small class="text-muted">Active until
                    {{ $currentPlan->end_date ? \Carbon\Carbon::parse($currentPlan->end_date)->format('M d, Y') : 'N/A' }}</small>
            @endif
            <a href="{{ url('/plans') }}" class="btn btn-light btn-sm w-100 mt-2">
                <i class="bi bi-star-fill me-2"></i>
                {{ $currentPlan->status === 'trial' ? 'Upgrade Now' : 'Manage Plan' }}
            </a>
        </div>
    @else
        <div class="trial-card">
            <h6 class="mb-2">No active plan found</h6>
            <a href="{{ url('/plans') }}" class="btn btn-light btn-sm w-100">
                <i class="bi bi-star-fill me-2"></i>
                Get Started
            </a>
        </div>
    @endif
</nav>
