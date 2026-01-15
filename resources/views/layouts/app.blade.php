<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Stock Management') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    @livewireStyles
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow mb-6">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="/" class="font-bold text-lg">Stock Management</a>
            <div class="space-x-4 flex items-center">
                <a href="/dashboard" class="hover:underline">Dashboard</a>
                <a href="/barang" class="hover:underline">Barang</a>
                <a href="/stok-barang" class="hover:underline">Stok Barang</a>
                <a href="/pelanggan" class="hover:underline">Pelanggan</a>
                <a href="/pemasok" class="hover:underline">Pemasok</a>
                <a href="/pembelian" class="hover:underline">Pembelian</a>
                <a href="/penjualan" class="hover:underline">Penjualan</a>
                <a href="/pengeluaran" class="hover:underline">Pengeluaran</a>
                <a href="/mutasi-stok" class="hover:underline">Mutasi Stok</a>
                <a href="/neraca" class="font-semibold text-blue-600 hover:underline">Laporan Neraca</a>

                <!-- Dropdown Keuangan -->
                <div class="relative group">
                    <button class="hover:underline font-semibold focus:outline-none">Keuangan ▾</button>
                    <div class="absolute left-0 mt-2 w-40 bg-white border rounded shadow-lg opacity-0 group-hover:opacity-100 group-hover:visible invisible transition-opacity duration-150 z-50">
                        <a href="/jurnal" class="block px-4 py-2 hover:bg-gray-100">Jurnal</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <main>
        @yield('content')
    </main>
    @livewireScripts
</body>
</html>
