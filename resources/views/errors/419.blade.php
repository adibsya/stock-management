<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Expired - Ngarumi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-50">
    <div class="flex min-h-screen items-center justify-center px-8 py-12">
        <div class="w-full max-w-lg px-4">
            <!-- Error Card -->
            <div class="bg-white rounded-2xl shadow-xl px-12 md:px-16 py-10 md:py-12 text-center">
                <!-- Icon -->
                <div class="mx-auto w-20 h-20 rounded-full bg-amber-100 flex items-center justify-center mb-8">
                    <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>

                <!-- Title -->
                <h1 class="text-3xl font-bold text-slate-900 mb-4">Session Expired</h1>
                <p class="text-slate-600 text-base leading-relaxed mb-10 px-4">
                    Halaman Anda telah kadaluarsa karena tidak aktif terlalu lama. Silakan login kembali untuk melanjutkan.
                </p>

                <!-- Actions -->
                <div class="space-y-4">
                    <a href="{{ route('login') }}" class="block w-full py-4 px-6 rounded-lg text-white font-semibold transition-all hover:opacity-90" style="background: linear-gradient(135deg, #1e3a5f 0%, #2d4a7c 100%);">
                        Kembali ke Login
                    </a>
                    <button onclick="window.history.back()" class="block w-full py-4 px-6 rounded-lg border-2 border-slate-300 text-slate-700 font-semibold hover:bg-slate-50 transition-all">
                        Kembali ke Halaman Sebelumnya
                    </button>
                </div>

                <!-- Info -->
                <div class="mt-10 pt-8 border-t border-slate-200">
                    <p class="text-sm text-slate-500 leading-relaxed">
                        💡 <strong>Tips:</strong> Session akan otomatis di-refresh setiap 5 menit untuk mencegah error ini.
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <p class="mt-12 text-center text-sm text-slate-500">
                © {{ date('Y') }} Ngarumi Stock Management System
            </p>
        </div>
    </div>
</body>
</html>
