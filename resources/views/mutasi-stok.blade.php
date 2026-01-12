<x-layouts.app :title="'Mutasi Stok'">
    <x-slot:header>
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">Mutasi Stok</h1>
            <span class="text-sm text-gray-500">Fitur pindah stok antar gudang (khusus superadmin)</span>
        </div>
    </x-slot:header>
    <div class="flex justify-end mt-6">
        <button onclick="window.dispatchEvent(new CustomEvent('open-mutasi-modal'))" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded shadow">Tambah Mutasi Stok</button>
    </div>
    <livewire:mutasi-stok-form />
    @livewire('mutasi-stok-table')
</x-layouts.app>
