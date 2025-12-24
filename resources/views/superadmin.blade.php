<x-layouts.app title="Superadmin Panel">
    <div class="space-y-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Superadmin Panel</h1>
            <p class="text-gray-600">Kelola multi-gudang dan manajemen akun admin per gudang.</p>
        </div>

        <!-- Multi Gudang Management -->
        <div class="card mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-700">Manajemen Gudang</h2>
                <a href="{{ route('gudang.index') }}" class="btn-primary">Kelola Gudang</a>
            </div>
            <livewire:gudang-table />
        </div>

        <!-- Akun Admin Per Gudang -->
        <div class="card mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-700">Manajemen Akun Admin Gudang</h2>
                <a href="{{ route('users.create') }}" class="btn-primary">Buat Akun Admin Gudang</a>
            </div>
            <livewire:user-table :showGudang="true" />
        </div>
    </div>
</x-layouts.app>
