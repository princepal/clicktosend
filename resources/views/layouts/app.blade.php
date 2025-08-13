<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('build/assets/images/icon.png') }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('build/assets/css/mystyle.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
    @include('layouts.navigation')
    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header class="header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <button class="btn btn-link text-muted me-3" id="sidebarToggle">
                        <i class="bi bi-arrow-left-right"></i>
                    </button>
                    <h4 class="mb-0">Welcome to Click To Send</h4>
                    {{-- <span class="badge bg-secondary ms-3">Trial</span> --}}
                </div>
                <div class="position-relative">
                    <div class="profile-pic" id="profileDropdown" style="cursor: pointer;">
                        {{ collect(explode(' ', Auth::user()->name))->map(fn($word) => strtoupper(substr($word, 0, 1)))->implode('') }}
                        <div class="online-indicator"></div>
                    </div>
                    <div class="dropdown-menu profile-dropdown" id="profileMenu" style="display: none;">
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person me-2"></i>
                            View Profile
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" class="dropdown-item text-danger"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                <i class="bi bi-box-arrow-right me-2"></i>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </div>
                </div>
            </div>
        </header>
        <!-- Inner Content -->
        <div ui-view>
            {{ $slot }}
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');

            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    if (window.innerWidth <= 768) {
                        // Mobile behavior
                        sidebar.classList.toggle('show');
                        console.log('Mobile toggle - show class:', sidebar.classList.contains('show'));
                    } else {
                        // Desktop behavior
                        sidebar.classList.toggle('hidden');
                        mainContent.classList.toggle('expanded');
                        console.log('Desktop toggle - hidden class:', sidebar.classList.contains('hidden'));
                    }
                });
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                        sidebar.classList.remove('show');
                    }
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    // Reset mobile classes when switching to desktop
                    sidebar.classList.remove('show');
                    sidebar.classList.remove('hidden');
                    mainContent.classList.remove('expanded');
                } else {
                    // Reset desktop classes when switching to mobile
                    sidebar.classList.remove('hidden');
                    mainContent.classList.remove('expanded');
                }
            });

            // Profile dropdown functionality
            const profileDropdown = document.getElementById('profileDropdown');
            const profileMenu = document.getElementById('profileMenu');

            if (profileDropdown && profileMenu) {
                profileDropdown.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    profileMenu.style.display = profileMenu.style.display === 'none' ? 'block' : 'none';
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!profileDropdown.contains(e.target) && !profileMenu.contains(e.target)) {
                        profileMenu.style.display = 'none';
                    }
                });

                // Close dropdown when pressing Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        profileMenu.style.display = 'none';
                    }
                });
            }

            // Add More Loadboard functionality
            const addLoadboardBtn = document.getElementById('addLoadboardBtn');
            if (addLoadboardBtn) {
                const addLoadboardModal = new bootstrap.Modal(document.getElementById('addLoadboardModal'));
                let selectedLoadboards = [];
                if (addLoadboardBtn) {
                    addLoadboardBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        addLoadboardModal.show();
                    });
                }
            }
        });
    </script>
</body>

</html>
