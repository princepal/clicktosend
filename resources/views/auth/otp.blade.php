<x-guest-layout>
    <section class="py-5">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-xl-10">
                    <div class="card rounded-3 text-black">
                        <div class="row g-0">
                            <div class="col-lg-6">
                                <div class="card-body p-md-5 mx-md-4">
                                    <div class="text-center">
                                        <h4 class="mt-1 mb-5 pb-1">Click To Send</h4>
                                    </div>
                                    @if (session('status'))
                                        <div class="alert alert-success text-center" role="alert">
                                            {{ session('status') }}
                                        </div>
                                    @endif
                                    <h3 class="text-center">Verify Your Email</h3>
                                    <p class="text-center text-muted mb-4">We've sent a 6-digit OTP to your email
                                        address</p>
                                    <form method="POST" action="{{ route('otp.verify') }}">
                                        @csrf
                                        <input type="hidden" name="email" value="{{ request('email') }}">
                                        <div data-mdb-input-init class="form-outline mb-4">
                                            <x-input-label class="form-label" for="otp" :value="__('Enter OTP')" />
                                            <x-text-input id="otp" class="form-control" type="text"
                                                name="otp" required autofocus maxlength="6" placeholder="000000" />
                                            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
                                        </div>
                                        <div class="text-center pt-1 mb-2 pb-1">
                                            <x-primary-button
                                                class="btn btn-primary w-100 mb-3">{{ __('Verify OTP') }}</x-primary-button>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-muted">Didn't receive the OTP?</p>
                                            <form method="POST" action="{{ route('otp.resend') }}"
                                                style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="email" value="{{ request('email') }}">
                                                <button type="submit"
                                                    class="btn btn-dark">{{ __('Resend OTP') }}</button>
                                            </form>
                                        </div>
                                        <div class="text-center mt-3">
                                            <a class="text-muted"
                                                href="{{ route('login') }}">{{ __('Back to Login') }}</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-lg-6 d-flex align-items-center gradient-custom"
                                style="background: url({{ asset('build/assets/images/formbackground.png') }});">
                                <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                                    <h4 class="mb-4">Email Verification</h4>
                                    <p class="small mb-0">Please check your email and enter the 6-digit OTP to complete
                                        your registration</p>
                                    <h3 class="mt-5">Secure and simple verification process</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
