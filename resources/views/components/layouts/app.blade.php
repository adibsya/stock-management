@props(['title' => 'Ngarumi - Sistem Manajemen Stok & POS', 'header' => 'Dashboard', 'subtitle' => null])

<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-gray-50">
    <div class="flex h-full">
        <!-- Sidebar -->
        <aside class="sidebar flex flex-col">
            <!-- Brand -->
            <div class="sidebar-brand flex-shrink-0">
                <div class="w-10 h-10 bg-gradient-to-br from-sky-500 to-sky-600 rounded-xl flex items-center justify-center shadow-lg shadow-sky-500/20">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div>
                    <span class="text-lg font-bold text-gray-900">Ngarumi</span>
                    <span class="block text-xs text-gray-400 -mt-0.5">Stock & POS</span>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="sidebar-nav flex-1 overflow-y-auto">
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>

                @if(auth()->user()->isSuperAdmin())
                <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    User Management
                </a>
                @endif
                
                @if(auth()->user()->canModify())
                <a href="{{ route('pos') }}" class="sidebar-link {{ request()->routeIs('pos') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Kasir / POS
                </a>
                @endif

                <div class="sidebar-section">
                    <p class="sidebar-section-title">Master Data</p>
                </div>
                
                <a href="{{ route('barang.index') }}" class="sidebar-link {{ request()->routeIs('barang.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Barang
                </a>
                
                <a href="{{ route('pelanggan.index') }}" class="sidebar-link {{ request()->routeIs('pelanggan.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Pelanggan
                </a>
                
                <a href="{{ route('pemasok.index') }}" class="sidebar-link {{ request()->routeIs('pemasok.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Pemasok
                </a>
                

                <a href="{{ route('gudang.index') }}" class="sidebar-link {{ request()->routeIs('gudang.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                    </svg>
                    Gudang
                </a>

                <a href="{{ route('stok.index') }}" class="sidebar-link {{ request()->routeIs('stok.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h18M3 7h18M3 11h18M3 15h18M3 19h18"></path>
                    </svg>
                    Stok
                </a>

                <div class="sidebar-section">
                    <p class="sidebar-section-title">Transaksi</p>
                </div>
                
                <a href="{{ route('penjualan.index') }}" class="sidebar-link {{ request()->routeIs('penjualan.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path>
                    </svg>
                    Penjualan
                </a>
                
                <a href="{{ route('pembelian.index') }}" class="sidebar-link {{ request()->routeIs('pembelian.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Pembelian
                </a>
                
                <a href="{{ route('pengeluaran.index') }}" class="sidebar-link {{ request()->routeIs('pengeluaran.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Pengeluaran
                </a>

                <div class="sidebar-section">
                    <p class="sidebar-section-title">Laporan</p>
                </div>
                
                <a href="{{ route('laporan.laba-rugi') }}" class="sidebar-link {{ request()->routeIs('laporan.laba-rugi') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Laba Rugi
                </a>
                
                <a href="{{ route('laporan.stok') }}" class="sidebar-link {{ request()->routeIs('laporan.stok') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    Kartu Stok
                </a>
            </nav>

            <!-- User Menu (Bottom) -->
            <div class="flex-shrink-0 p-4 border-t border-gray-100 bg-white mt-auto">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-sky-500 to-sky-600 rounded-full flex items-center justify-center text-white font-semibold text-sm shadow-sm flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->role_label }}</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="flex-shrink-0">
                        @csrf
                        <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Logout">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-64 flex flex-col min-h-screen">
            <!-- Top Header -->
            <header class="top-header">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h1 class="page-title">{{ $header }}</h1>
                        @if($subtitle)
                            <p class="page-subtitle">{{ $subtitle }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">{{ now()->translatedFormat('l') }}</p>
                            <p class="text-xs text-gray-500">{{ now()->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Toast Notifications -->
            @if(session('success'))
                <div id="toast-success" class="fixed top-4 right-4 z-50 flex items-center gap-3 bg-white border border-emerald-200 shadow-lg rounded-lg px-4 py-3 fade-in">
                    <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <span class="text-sm text-gray-700">{{ session('success') }}</span>
                    <button onclick="document.getElementById('toast-success').remove()" class="ml-2 text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <script>setTimeout(() => { const el = document.getElementById('toast-success'); if(el) el.remove(); }, 4000);</script>
            @endif

            @if(session('error'))
                <div id="toast-error" class="fixed top-4 right-4 z-50 flex items-center gap-3 bg-white border border-red-200 shadow-lg rounded-lg px-4 py-3 fade-in">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <span class="text-sm text-gray-700">{{ session('error') }}</span>
                    <button onclick="document.getElementById('toast-error').remove()" class="ml-2 text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <script>setTimeout(() => { const el = document.getElementById('toast-error'); if(el) el.remove(); }, 5000);</script>
            @endif

            <!-- Page Content -->
            <div class="flex-1 p-6">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <footer class="px-6 py-4 text-center text-xs text-gray-400 border-t border-gray-100 bg-white/50">
                &copy; {{ date('Y') }} Ngarumi POS. All rights reserved.
            </footer>
        </main>
    </div>

    @livewireScripts
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('notify', (event) => {
                const message = event.message || event[0]?.message;
                if (message) {
                    alert(message);
                }
            });
        });
    </script>
</body>
</html>
