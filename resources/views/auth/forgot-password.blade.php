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
                                    <form method="POST" action="{{ route('password.email') }}">
                                        @csrf
                                        <p>{{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                                        </p>
                                        <div data-mdb-input-init class="form-outline mb-4">
                                            <x-input-label class="form-label" for="email" :value="__('Email')" />
                                            <x-text-input id="email" class="form-control" type="email"
                                                name="email" :value="old('email')" required autofocus
                                                autocomplete="username" />
                                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                        </div>

                                        <div class="text-center pt-1 mb-5 pb-1">
                                            <x-primary-button
                                                class="btn btn-primary w-100 mb-3">{{ __('Email Password Reset Link') }}</x-primary-button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-lg-6 d-flex align-items-center gradient-custom"
                                style="background: url({{ asset('build/assets/images/formbackground.png') }});">
                                <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                                    <h4 class="mb-4">Introducing Click To Send</h4>
                                    <p class="small mb-0">Save time, reduce hassle, and manage loads effortlessly -
                                        built for brokers and carriers</p>
                                    <h3 class="mt-5">Simple, secure, and free - the trusted extension for brokers &
                                        carriers</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
