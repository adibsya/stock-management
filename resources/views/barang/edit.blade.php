<x-layouts.app>
    <x-slot name="header">
        Edit Barang (Identitas)
    </x-slot>

    <div>
        @livewire('barang-master-form', ['barangMaster' => $barangMaster])
    </div>
</x-layouts.app>
