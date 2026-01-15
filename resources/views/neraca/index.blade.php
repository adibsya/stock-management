
<x-layouts.app header="Laporan Neraca">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            @livewire('neraca-form')
        </div>
        <div>
            @livewire('neraca-table')
        </div>
    </div>
</x-layouts.app>
