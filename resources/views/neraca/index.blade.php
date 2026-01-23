
<x-layouts.app header="Laporan Neraca">
    <div class="flex flex-col lg:flex-row gap-6">
        {{-- Input Form (Sidebar) --}}
        <div class="lg:w-96 flex-shrink-0">
            @livewire('neraca-form')
        </div>
        {{-- Table View (Main Content) --}}
        <div class="flex-1 min-w-0">
            @livewire('neraca-table')
        </div>
    </div>
</x-layouts.app>
