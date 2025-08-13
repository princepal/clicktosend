<x-app-layout>
    <div class="container-fluid p-4">
        <div class="row justify-content-center">
            <div class="col-12">
                <!-- Pricing Header -->
                <div class="pricing-header">
                    <h1>Adaptive Plans that suits you!</h1>
                    <p>Navigate growth seamlessly with our Adaptive Plans, meticulously designed to accommodate you at
                        every stage of their evolution.</p>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-10 col-md-6 mb-4">
                        @if ($planexpired != '')
                            <div class="alert alert-danger mt-3" id="failureMessage">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    <div>
                                        <strong>Error!</strong> {{ $planexpired }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success mt-3" id="successMessage">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    <div>
                                        <strong>Success!</strong> {{ session('success') }}
                                    </div>
                                </div>
                            </div>
                            <script>
                                const successMessage = document.getElementById('successMessage');
                                setTimeout(() => {
                                    successMessage.style.display = 'none';
                                }, 3000);
                            </script>
                        @endif
                    </div>
                </div>
                <!-- Pricing Cards -->
                <div class="row justify-content-center">
                    @foreach ($plans as $plan)
                        @php
                            $per = $plan->frequency === 'Monthly' ? 'month' : 'year';
                            $basePrice = $plan->sale_price ?? $plan->price; // Price per dispatcher
                        @endphp

                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="pricing-card border p-4 rounded shadow-sm h-100">

                                {{-- Plan Title & Description --}}
                                <h5 class="fw-bold mb-2">{{ $plan->plan_name }}</h5>
                                <p class="text-muted small">{{ $plan->short_description }}</p>

                                {{-- Price per dispatcher --}}
                                <div class="mb-3">
                                    <span class="fs-2 fw-bold">${{ $basePrice }}</span>
                                    <span class="text-muted">/dispatcher</span>
                                    <div class="small text-muted">
                                        billed at <span
                                            class="billing-total fw-bold">${{ $basePrice * 1 }}</span>/{{ $per }}
                                    </div>
                                </div>

                                {{-- Dispatcher Selector --}}
                                <div class="input-group mb-3">
                                    <button class="btn btn-outline-secondary decrement" type="button">-</button>
                                    <input type="number" class="form-control dispatcher-count text-center"
                                        value="1" min="1">
                                    <button class="btn btn-outline-secondary increment" type="button">+</button>
                                    <span class="input-group-text">dispatchers</span>
                                </div>

                                {{-- Subscribe Button --}}
                                @if (isset($activePlanId) && $activePlanId == $plan->id && \Illuminate\Support\Carbon::parse($activeEndDate)->isFuture())
                                    <button class="btn btn-success w-100" disabled>
                                        Subscribed (until
                                        {{ \Illuminate\Support\Carbon::parse($activeEndDate)->format('Y-m-d') }})
                                    </button>
                                @else
                                    <a href="{{ route('plans.subscribe', $plan->id) }}"
                                        class="btn btn-primary w-100 get-started-btn">Get started</a>
                                @endif

                                {{-- Features List --}}
                                <ul class="list-unstyled mt-3 mb-0">
                                    {!! $plan->description !!}
                                </ul>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".pricing-card").forEach(card => {
                const basePrice = parseFloat(card.querySelector(".fs-2").textContent.replace("$", ""));
                const countInput = card.querySelector(".dispatcher-count");
                const billingTotal = card.querySelector(".billing-total");

                card.querySelector(".increment").addEventListener("click", () => {
                    countInput.value = parseInt(countInput.value);
                    billingTotal.textContent = "$" + (basePrice * countInput.value);
                });

                card.querySelector(".decrement").addEventListener("click", () => {
                    if (countInput.value > 1) {
                        countInput.value = parseInt(countInput.value) - 1;
                        billingTotal.textContent = "$" + (basePrice * countInput.value);
                    }
                });

                countInput.addEventListener("input", () => {
                    const val = Math.max(1, parseInt(countInput.value) || 1);
                    countInput.value = val;
                    billingTotal.textContent = "$" + (basePrice * val);
                });
            });
        });

        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".pricing-card").forEach(card => {
                const basePrice = parseFloat(card.querySelector(".fs-2").textContent.replace("$", ""));
                const countInput = card.querySelector(".dispatcher-count");
                const billingTotal = card.querySelector(".billing-total");
                const getStartedBtn = card.querySelector(".get-started-btn");

                card.querySelector(".increment").addEventListener("click", () => {
                    countInput.value = parseInt(countInput.value) + 1;
                    billingTotal.textContent = "$" + (basePrice * countInput.value);
                });

                card.querySelector(".decrement").addEventListener("click", () => {
                    if (countInput.value > 1) {
                        countInput.value = parseInt(countInput.value) - 1;
                        billingTotal.textContent = "$" + (basePrice * countInput.value);
                    }
                });

                countInput.addEventListener("input", () => {
                    const val = Math.max(1, parseInt(countInput.value) || 1);
                    countInput.value = val;
                    billingTotal.textContent = "$" + (basePrice * val);
                });

                getStartedBtn.addEventListener("click", (e) => {
                    // Append ?dispatchers=<count> to the URL
                    e.preventDefault();
                    const url = new URL(getStartedBtn.href);
                    url.searchParams.set("dispatchers", countInput.value);
                    window.location.href = url.toString();
                });
            });
        });
    </script>
</x-app-layout>
