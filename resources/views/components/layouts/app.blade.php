@props(['title' => 'Penak - Sistem Manajemen Stok & POS', 'header' => '', 'subtitle' => null])

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="h-full bg-gray-50">
    <div class="flex h-full">
        <!-- Sidebar -->
        <aside class="sidebar flex flex-col">
            <!-- Brand -->
            <div class="sidebar-brand flex-shrink-0 flex flex-col items-center mt-4">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="w-32 h-10" style="margin-top:-12px;">
                <span class="block text-xs text-gray-400 -mt-0.5">Stock & POS</span>
            </div>
            
            <!-- Navigation -->
            <nav class="sidebar-nav flex-1 overflow-y-auto">
                @php $isViewer = auth()->user()->role === 'viewer'; @endphp
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>

                @if(auth()->user()->isSuperAdmin() && !$isViewer)
                <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    User Management
                </a>
                @endif

                @if(auth()->user()->canModify() && !$isViewer)
                <a href="{{ route('pos.index') }}" class="sidebar-link {{ request()->routeIs('pos.index') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Kasir / POS
                </a>
                @endif

                <div class="sidebar-section">
                    <p class="sidebar-section-title">Master Data</p>
                </div>

                <a href="{{ route('pos-master-data.index') }}"
                    class="sidebar-link {{ request()->routeIs('pos-master-data.*') ? 'active' : '' }}">

                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M3 3h18M3 7h18M3 11h18" />
                    </svg>

                    <span>Pos Master Data</span>
                </a>

                <a href="{{ route('barang.index') }}" class="sidebar-link {{ request()->routeIs('barang.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 10h16v11H4V10z"></path>
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

                @if(!$isViewer)
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

                <a href="{{ url('/pengeluaran') }}" class="sidebar-link {{ request()->is('pengeluaran*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Pengeluaran
                </a>

                <a href="{{ route('retur.index') }}" class="sidebar-link {{ request()->routeIs('retur.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                    </svg>
                    Retur
                </a>
                @endif

                <div class="sidebar-section">
                    <p class="sidebar-section-title">Keuangan</p>
                </div>

                    <a href="{{ route('jurnal.index') }}" class="sidebar-link {{ request()->routeIs('jurnal.index') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2"></path>
                        </svg>
                        Jurnal
                    </a>

                    <a href="{{ route('trial-balance.index') }}" class="sidebar-link {{ request()->routeIs('trial-balance.index') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 17h16M4 13h16M4 9h16M4 5h16" />
                        </svg>
                        Trial Balance
                    </a>

                    <a href="{{ route('buku-besar.index') }}" class="sidebar-link {{ request()->routeIs('buku-besar.index') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 17h16M4 13h16M4 9h16M4 5h16" />
                        </svg>
                        Buku Besar
                    </a>
                    
                    <a href="{{ route('jurnal-umum.create') }}" class="sidebar-link {{ request()->routeIs('jurnal-umum.create') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h18M3 7h18M3 11h18M3 15h18M3 19h18"></path>
                        </svg>
                        Jurnal Umum
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

                <a href="{{ route('neraca.index') }}" class="sidebar-link {{ request()->routeIs('neraca.index') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h18M3 7h18M3 11h18M3 15h18M3 19h18"></path>
                    </svg>
                    Neraca
                </a>
            </nav>

            <!-- User Menu (Bottom) -->
            <div class="flex-shrink-0 p-4 border-t border-gray-100 bg-white mt-auto">
                <div class="flex items-center gap-3">
                    @if(auth()->user()->foto)
                        <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="Foto Profil" class="w-10 h-10 rounded-full object-cover shadow-sm flex-shrink-0 cursor-pointer border-2 border-sky-500" onclick="window.location='{{ route('profil') }}'" title="Lihat Profil">
                    @else
                        <div class="w-10 h-10 bg-gradient-to-br from-sky-500 to-sky-600 rounded-full flex items-center justify-center text-white font-semibold text-sm shadow-sm flex-shrink-0 cursor-pointer" onclick="window.location='{{ route('profil') }}'" title="Lihat Profil">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                    @endif
                    <div class="flex-1 min-w-0 cursor-pointer" onclick="window.location='{{ route('profil') }}'" title="Lihat Profil">
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
                        <h1 class="page-title text-2xl font-bold text-gray-900 tracking-tight">{{ $header }}</h1>
                        @if($subtitle)
                            <p class="page-subtitle text-sm text-gray-500 mt-1">{{ $subtitle }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex flex-col items-end">
                            <span class="text-xs text-gray-500 uppercase tracking-wider" id="local-day"></span>
                            <span class="text-sm text-gray-700 mt-0.5" id="local-date"></span>
                            <span class="text-xs font-mono text-gray-600 mt-0.5" id="local-time"></span>
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
        </main>
    </div>

    @livewireScripts
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('notify', (event) => {
                const message = event.message || event[0]?.message;
                if (message) {
                    // Create toast container if not exists
                    let container = document.getElementById('toast-notify-container');
                    if (!container) {
                        container = document.createElement('div');
                        container.id = 'toast-notify-container';
                        container.className = 'fixed top-5 right-5 z-[9999] flex flex-col gap-3';
                        document.body.appendChild(container);
                    }

                    // Create toast element
                    const toast = document.createElement('div');
                    toast.className = 'group flex items-center gap-3 bg-white border border-emerald-200 shadow-xl rounded-2xl px-5 py-4 min-w-[320px] max-w-[420px] transform translate-x-full opacity-0 transition-all duration-500 ease-out hover:shadow-2xl hover:scale-[1.02]';
                    toast.innerHTML = `
                        <div class="relative">
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-emerald-200">
                                <svg class="w-6 h-6 text-white animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="absolute -top-1 -right-1 w-5 h-5 bg-emerald-500 rounded-full flex items-center justify-center shadow-md animate-ping-slow">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-emerald-700">Berhasil!</p>
                            <p class="text-sm text-gray-600 mt-0.5 leading-snug">${message}</p>
                        </div>
                        <button class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-all opacity-0 group-hover:opacity-100" onclick="this.parentElement.remove()">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        <div class="absolute bottom-0 left-0 right-0 h-1 bg-gray-100 rounded-b-2xl overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-emerald-400 to-emerald-600 toast-progress"></div>
                        </div>
                    `;
                    container.appendChild(toast);

                    // Add animation styles if not exists
                    if (!document.getElementById('toast-notify-styles')) {
                        const style = document.createElement('style');
                        style.id = 'toast-notify-styles';
                        style.textContent = `
                            @keyframes toast-progress {
                                from { width: 100%; }
                                to { width: 0%; }
                            }
                            @keyframes ping-slow {
                                0%, 100% { transform: scale(1); opacity: 1; }
                                50% { transform: scale(1.2); opacity: 0.7; }
                            }
                            .toast-progress {
                                animation: toast-progress 3s linear forwards;
                            }
                            .animate-ping-slow {
                                animation: ping-slow 1s ease-in-out 2;
                            }
                        `;
                        document.head.appendChild(style);
                    }

                    // Trigger animation
                    requestAnimationFrame(() => {
                        toast.classList.remove('translate-x-full', 'opacity-0');
                        toast.classList.add('translate-x-0', 'opacity-100');
                    });

                    // Auto remove with animation
                    setTimeout(() => {
                        toast.classList.add('translate-x-full', 'opacity-0');
                        setTimeout(() => toast.remove(), 500);
                    }, 3000);
                }
            });

            Livewire.on('show-alert', (event) => {
                const data = event[0] || event;
                Swal.fire({
                    icon: data.type,
                    title: data.type === 'success' ? 'Berhasil!' : 'Gagal!',
                    text: data.message,
                    confirmButtonColor: data.type === 'success' ? '#10b981' : '#ef4444'
                });
            });
        });
    </script>
    @stack('scripts')

        <script>
            function updateLocalTime() {
                const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
                const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                const now = new Date();
                document.getElementById('local-day').textContent = days[now.getDay()];
                document.getElementById('local-date').textContent = `${now.getDate().toString().padStart(2,'0')} ${months[now.getMonth()]} ${now.getFullYear()}`;
                document.getElementById('local-time').textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            }
            updateLocalTime();
            setInterval(updateLocalTime, 1000);
        </script>
    
</body>
</html>
