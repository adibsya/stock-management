<x-layouts.app title="User Management">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">User Management</h1>
                <p class="text-gray-600 mt-1">Kelola akun admin dan viewer</p>
            </div>
        </div>

        <!-- User Table Component -->
        <livewire:user-table />
    </div>
</x-layouts.app>
