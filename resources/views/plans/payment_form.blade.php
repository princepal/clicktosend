<x-app-layout>
    <style>
        /* Payment specific styles */
        .payment-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), #8e44ad);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }

        .card-number-input {
            font-family: 'Courier New', monospace;
            font-size: 1.1rem;
            letter-spacing: 2px;
        }

        .card-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.5rem;
            color: var(--text-muted);
        }

        .card-field {
            position: relative;
        }

        .security-badge {
            background: #28a745;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .payment-summary {
            background: #f8f9fa;
            border-radius: 0.5rem;
            padding: 1.5rem;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .summary-total {
            border-top: 2px solid #e9ecef;
            padding-top: 1rem;
            margin-top: 1rem;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .cvv-info {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-top: 0.5rem;
        }

        .save-card-option {
            background: #f8f9fa;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-top: 1rem;
        }

        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    <div class="container-fluid p-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Back Link -->
                <a href="{{ url('/plans') }}" class="back-link">
                    <i class="bi bi-arrow-left me-2"></i>
                    Back to Pricing
                </a>

                <div class="row">
                    <!-- Payment Form -->
                    <div class="col-lg-8">
                        <div class="payment-card">
                            <div class="card-header">
                                <h4 class="mb-1">
                                    <i class="bi bi-credit-card me-2"></i>
                                    Secure Payment
                                </h4>
                                <p class="mb-0">Complete your subscription upgrade</p>
                            </div>
                            <div class="card-body p-4">
                                @if (session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif
                                <!-- Payment Form dd -->
                                <form id="paymentForm" method="POST"
                                    action="{{ route('plans.subscribe', $plan->id) }}">
                                    @csrf
                                    <!-- Card Information -->
                                    <div class="mb-4">
                                        <h6 class="mb-3">Card Information</h6>
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <label for="name" class="form-label">Name on Card</label>
                                                <div class="card-field">
                                                    <input type="text" class="form-control card-number-input"
                                                        name="name" id="name" value="{{ old('name') }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <label for="cardNumber" class="form-label">Card Number</label>
                                                <div class="card-field">
                                                    <input type="text" class="form-control card-number-input"
                                                        name="card_number" id="cardNumber"
                                                        placeholder="1234 5678 9012 3456" maxlength="19"
                                                        value="{{ old('card_number') }}" required>
                                                    <i class="bi bi-credit-card card-icon"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="expiry_month" class="form-label">Expiry Month (MM)</label>
                                                <input type="text" class="form-control" id="expiry_month"
                                                    name="expiry_month" placeholder="MM" maxlength="2"
                                                    value="{{ old('expiry_month') }}" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="expiry_year" class="form-label">Expiry Year (YYYY)</label>
                                                <input type="text" class="form-control" id="expiry_year"
                                                    name="expiry_year" placeholder="YYYY" maxlength="4"
                                                    value="{{ old('expiry_year') }}" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="cvv" class="form-label">CVV</label>
                                                <input type="text" class="form-control" id="cvd" name="cvd"
                                                    placeholder="123" maxlength="4" value="{{ old('cvd') }}"
                                                    required>
                                                <div class="cvv-info">
                                                    <i class="bi bi-info-circle me-1"></i>
                                                    3 or 4 digit security code
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Security Badge -->
                                    <div class="d-flex align-items-center justify-content-center mb-4">
                                        <span class="security-badge me-2">
                                            <i class="bi bi-shield-check me-1"></i>
                                            SSL Secured
                                        </span>
                                        <span class="security-badge me-2">
                                            <i class="bi bi-lock me-1"></i>
                                            PCI Compliant
                                        </span>
                                        <span class="security-badge">
                                            <i class="bi bi-encryption me-1"></i>
                                            256-bit Encryption
                                        </span>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg" id="payButton">
                                            <span class="loading-spinner me-2" id="loadingSpinner"></span>
                                            <i class="bi bi-lock me-2"></i>
                                            Pay {{ number_format($plan->price * $dispatchers, 2) }}
                                        </button>
                                    </div>
                                </form>

                                {{-- <div class="alert alert-success mt-3" id="successMessage" style="display: none;">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-check-circle-fill me-2"></i>
                                        <div>
                                            <strong>Payment Successful!</strong> Your subscription has been upgraded.
                                        </div>
                                    </div>
                                </div>
                                <div class="alert alert-danger mt-3" id="errorMessage" style="display: none;">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        <div>
                                            <strong>Payment Failed!</strong> Please check your card details and try
                                            again.
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>

                    <!-- Payment Summary -->
                    <div class="col-lg-4">
                        <div class="payment-summary">
                            <h5 class="mb-3">Order Summary</h5>

                            <div class="summary-item">
                                <span>Pro Plan</span>
                                <span>{{ $plan->formatted_price }}</span>
                            </div>
                            <div class="summary-item">
                                <span>Dispatchers</span>
                                <span>{{ $dispatchers }}</span>
                            </div>
                            {{-- <div class="summary-item">
                                <span>Tax</span>
                                <span>$8.91</span>
                            </div> --}}

                            <div class="summary-item summary-total">
                                <span>Total</span>
                                <span>{{ number_format($plan->price * $dispatchers, 2) }}</span>
                            </div>

                            <hr class="my-3">

                            <h6 class="mb-2">What's Included:</h6>
                            {!! $plan->description !!}

                            {{-- <div class="mt-3 p-3 bg-light rounded">
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Your subscription will automatically renew monthly. You can cancel anytime.
                                </small>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Card number formatting
        document.getElementById('cardNumber').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        });

        // Expiry date formatting
        /*document.getElementById('expiryDate').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.slice(0, 2) + '/' + value.slice(2, 4);
            }
            e.target.value = value;
        });*/

        // CVV formatting
        document.getElementById('cvd').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });
        document.getElementById('expiry_month').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });
        document.getElementById('expiry_year').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });
    </script>
</x-app-layout>
