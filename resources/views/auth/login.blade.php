<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Ngarumi Stock Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-50">
    <div class="flex min-h-full relative">
        <!-- Left Side - Branding -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-[#1e3a5f] overflow-hidden" style="clip-path: polygon(0 0, 100% 0, 85% 100%, 0 100%);">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <svg class="absolute h-full w-full" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid" width="32" height="32" patternUnits="userSpaceOnUse">
                            <path d="M0 32V0h32" fill="none" stroke="currentColor" stroke-width="0.5" class="text-white"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid)"/>
                </svg>
            </div>

            <!-- Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-br from-[#1e3a5f] via-[#2d4a7c] to-[#1e3a5f]"></div>

            <!-- Content -->
            <div class="relative z-10 flex flex-col justify-center items-center w-full px-12 py-12">
                <!-- Logo -->
                <div class="mb-6">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/20">
                        <svg class="w-12 h-12 text-white" viewBox="0 0 100 100" fill="currentColor">
                            <path d="M50 15 C40 30, 35 50, 38 65 C41 78, 48 85, 50 85 C52 85, 59 78, 62 65 C65 50, 60 30, 50 15 Z" opacity="0.9"/>
                            <path d="M35 25 C20 40, 15 60, 22 75 C27 85, 38 88, 45 82 C38 78, 32 65, 32 50 C32 40, 35 30, 35 25 Z" opacity="0.5"/>
                            <path d="M65 25 C80 40, 85 60, 78 75 C73 85, 62 88, 55 82 C62 78, 68 65, 68 50 C68 40, 65 30, 65 25 Z" opacity="0.5"/>
                        </svg>
                    </div>
                </div>

                <!-- Brand Text -->
                <h1 class="text-4xl font-bold text-white tracking-wide mb-2">NGARUMI</h1>
                <p class="text-blue-200/80 text-sm tracking-widest uppercase mb-16">Stock Management System</p>

                <!-- Features List -->
                <div class="space-y-6 max-w-xs">
                    <div class="flex items-start gap-4 text-white/80">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-sm leading-relaxed">Real-time inventory tracking</span>
                    </div>
                    <div class="flex items-start gap-4 text-white/80">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <span class="text-sm leading-relaxed">Comprehensive analytics</span>
                    </div>
                    <div class="flex items-start gap-4 text-white/80">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <span class="text-sm leading-relaxed">Secure & reliable</span>
                    </div>
                </div>
            </div>

            <!-- Bottom Decoration -->
            <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="flex flex-1 flex-col justify-center px-6 py-12 lg:px-8 xl:px-24">
            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden flex justify-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-xl bg-[#1e3a5f]">
                        <svg class="w-10 h-10 text-white" viewBox="0 0 100 100" fill="currentColor">
                            <path d="M50 15 C40 30, 35 50, 38 65 C41 78, 48 85, 50 85 C52 85, 59 78, 62 65 C65 50, 60 30, 50 15 Z" opacity="0.9"/>
                            <path d="M35 25 C20 40, 15 60, 22 75 C27 85, 38 88, 45 82 C38 78, 32 65, 32 50 C32 40, 35 30, 35 25 Z" opacity="0.5"/>
                            <path d="M65 25 C80 40, 85 60, 78 75 C73 85, 62 88, 55 82 C62 78, 68 65, 68 50 C68 40, 65 30, 65 25 Z" opacity="0.5"/>
                        </svg>
                    </div>
                </div>

                <!-- Header -->
                <h2 class="text-center text-2xl font-bold tracking-tight text-slate-900 lg:text-left mb-2">
                    Sign in to your account
                </h2>
                <p class="text-center text-sm text-slate-600 lg:text-left mb-8">
                    Enter your credentials to access the dashboard
                </p>
            </div>

            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <!-- Success Message -->
                @if(session('success'))
                    <div class="mb-6 rounded-lg bg-green-50 p-4 border border-green-200">
                        <div class="flex items-center gap-3">
                            <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Error Message -->
                @if($errors->any())
                    <div class="mb-6 rounded-lg bg-red-50 p-4 border border-red-200">
                        <div class="flex items-center gap-3">
                            <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm font-medium text-red-800">{{ $errors->first() }}</p>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                            Email address
                        </label>
                        <div>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                autocomplete="email"
                                required
                                autofocus
                                value="{{ old('email') }}"
                                placeholder="you@example.com"
                                class="block w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 placeholder-slate-400 shadow-sm transition-colors focus:border-[#2d4a7c] focus:outline-none focus:ring-2 focus:ring-[#2d4a7c]/20 sm:text-sm"
                            >
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-sm font-medium text-slate-700">
                                Password
                            </label>
                            <a href="#" class="text-sm font-medium text-[#2d4a7c] hover:text-[#1e3a5f] transition-colors">
                                Forgot password?
                            </a>
                        </div>
                        <div class="relative">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                autocomplete="current-password"
                                required
                                placeholder="••••••••"
                                class="block w-full rounded-lg border border-slate-300 px-4 py-3 pr-12 text-slate-900 placeholder-slate-400 shadow-sm transition-colors focus:border-[#2d4a7c] focus:outline-none focus:ring-2 focus:ring-[#2d4a7c]/20 sm:text-sm"
                            >
                            <button
                                type="button"
                                onclick="togglePassword()"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 transition-colors"
                                aria-label="Toggle password visibility"
                            >
                                <svg id="eye-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg id="eye-closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input
                            id="remember"
                            name="remember"
                            type="checkbox"
                            class="h-4 w-4 rounded border-slate-300 text-[#2d4a7c] focus:ring-[#2d4a7c]/20"
                        >
                        <label for="remember" class="ml-3 text-sm text-slate-600">
                            Remember me for 30 days
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button
                            type="submit"
                            class="flex w-full justify-center rounded-lg bg-[#2d4a7c] px-4 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:bg-[#1e3a5f] focus:outline-none focus:ring-2 focus:ring-[#2d4a7c] focus:ring-offset-2"
                        >
                            Sign in
                        </button>
                    </div>
                </form>

                <!-- Footer -->
                <p class="mt-12 text-center text-xs text-slate-500">
                    © {{ date('Y') }} Ngarumi. All rights reserved.
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }

        // Auto-refresh CSRF token every 5 minutes to prevent 419 error
        setInterval(function() {
            fetch('/refresh-csrf')
                .then(response => response.json())
                .then(data => {
                    document.querySelector('input[name="_token"]').value = data.token;
                    document.querySelector('meta[name="csrf-token"]').content = data.token;
                })
                .catch(error => console.log('CSRF refresh failed:', error));
        }, 300000); // 5 minutes

        // Handle form submission with CSRF error retry
        document.querySelector('form').addEventListener('submit', function(e) {
            const form = this;
            
            // Check if already submitted
            if (form.dataset.submitting === 'true') {
                e.preventDefault();
                return;
            }
            
            form.dataset.submitting = 'true';
            
            // Re-enable form after 3 seconds to allow retry
            setTimeout(function() {
                form.dataset.submitting = 'false';
            }, 3000);
        });
    </script>
</body>
</html>
