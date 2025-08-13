<x-app-layout>
    <div class="container-fluid p-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-6">
                <!-- Back Link -->
                <a href="dashboard.html" class="back-link">
                    <i class="bi bi-arrow-left me-2"></i>
                    Back to Dashboard
                </a>

                <!-- Profile Update Form -->
                <div class="card">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div class="profile-pic mx-auto mb-3" style="width: 80px; height: 80px; font-size: 1.5rem;">
                                JD
                            </div>
                            <h4 class="mb-1">Update Profile</h4>
                            <p class="text-muted">Update your account information</p>
                        </div>
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Success:</strong> Your profile or password have been updated.
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="mb-4">
                                <ul class="text-red-600 text-sm list-disc pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="post" action="{{ route('profile.update.combined') }}" class="mt-6 space-y-6"
                            id="updateProfileForm">
                            @csrf
                            @method('put')
                            <div class="mb-4">
                                <label for="fullName" class="form-label">Full Name</label>
                                <x-text-input id="fullName" name="name" type="text" class="form-control"
                                    :value="old('name', $user->name)" required autofocus autocomplete="name" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div class="mb-4">
                                <label for="email" class="form-label">Email Address</label>
                                <x-text-input id="email" name="email" type="email" class="form-control"
                                    :value="old('email', $user->email)" required autocomplete="username" readonly />
                                <div class="form-text">Email address cannot be changed</div>
                            </div>

                            <div class="mb-4">
                                <label for="newPassword" class="form-label">New Password</label>
                                <div class="password-field">
                                    <x-text-input id="newPassword" name="password" type="password" class="form-control"
                                        autocomplete="new-password" />
                                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                                    <button type="button" class="password-toggle"
                                        onclick="togglePassword('newPassword')">
                                        <i class="bi bi-eye" id="newPasswordIcon"></i>
                                    </button>
                                </div>
                                {{-- <div class="form-text">Password must be at least 8 characters long</div> --}}
                            </div>

                            <div class="mb-4">
                                <label for="confirmPassword" class="form-label">Confirm New Password</label>
                                <div class="password-field">
                                    <x-text-input id="confirmPassword" name="password_confirmation" type="password"
                                        class="form-control" autocomplete="new-password" />
                                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                                    <button type="button" class="password-toggle"
                                        onclick="togglePassword('confirmPassword')">
                                        <i class="bi bi-eye" id="confirmPasswordIcon"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- <div class="alert alert-info" role="alert">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Note:</strong> You'll need to enter your current password to save any changes.
                            </div> --}}

                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                                    Cancel
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Update Profile
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + 'Icon');

            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>
</x-app-layout>
