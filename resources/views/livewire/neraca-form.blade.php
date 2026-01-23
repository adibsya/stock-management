<div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
        </div>
        <h2 class="text-lg font-bold text-gray-800">Input Neraca</h2>
    </div>

    @if(session()->has('success'))
        <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm font-medium text-emerald-700">{{ session('success') }}</p>
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-5">
        {{-- Tanggal --}}
        <div class="space-y-2">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</label>
            <input type="date" 
                   wire:model="tanggal" 
                   class="w-full px-4 py-3 bg-gray-50/80 border border-gray-200 rounded-xl text-sm text-gray-700 shadow-sm hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400/50 focus:border-blue-400 focus:bg-white transition-all duration-200" />
            @error('tanggal') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        {{-- POS Dropdown with Alpine.js --}}
        <div class="space-y-2" x-data="{ open: false, selected: null }" @click.away="open = false">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Pos Akun</label>
            <div class="relative">
                <button @click="open = !open" type="button"
                        class="w-full px-4 py-3 bg-gray-50/80 border border-gray-200 rounded-xl text-sm text-left text-gray-700 shadow-sm hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400/50 focus:border-blue-400 focus:bg-white transition-all duration-200 flex items-center justify-between">
                    <span>
                        @if($pos_id)
                            {{ $posList->firstWhere('id', $pos_id)?->kode }} - {{ $posList->firstWhere('id', $pos_id)?->nama }}
                        @else
                            <span class="text-gray-400">Pilih Pos Akun...</span>
                        @endif
                    </span>
                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute z-50 w-full mt-2 bg-white border border-gray-100 rounded-2xl shadow-xl overflow-hidden">
                    <div class="py-2 max-h-60 overflow-y-auto">
                        @foreach($posList as $pos)
                        <button @click="$wire.set('pos_id', '{{ $pos->id }}'); open = false" type="button"
                                class="w-full px-4 py-2.5 text-left text-sm flex items-center gap-3 transition-colors
                                {{ $pos_id == $pos->id ? 'bg-blue-50 text-blue-600 font-medium' : 'hover:bg-gray-50 text-gray-700' }}">
                            <span class="w-4 flex-shrink-0">
                                @if($pos_id == $pos->id)
                                <svg class="w-3.5 h-3.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                @endif
                            </span>
                            <div class="flex flex-col">
                                <span class="font-medium">{{ $pos->kode }}</span>
                                <span class="text-xs text-gray-500">{{ $pos->nama }}</span>
                            </div>
                            <span class="ml-auto text-xs px-2 py-0.5 rounded-full {{ $pos->jenis === 'debit' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ ucfirst($pos->jenis) }}
                            </span>
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>
            @error('pos_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        {{-- Jumlah --}}
        <div class="space-y-2">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 font-medium text-sm">Rp</span>
                <input type="number" 
                       wire:model="jumlah" 
                       step="0.01"
                       placeholder="0"
                       class="w-full pl-12 pr-4 py-3 bg-gray-50/80 border border-gray-200 rounded-xl text-sm text-gray-700 shadow-sm hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400/50 focus:border-blue-400 focus:bg-white transition-all duration-200" />
            </div>
            @error('jumlah') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        {{-- Submit Button --}}
        <button type="submit" 
                class="w-full px-5 py-3.5 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-blue-500/30 transition-all duration-200 hover:shadow-xl flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Simpan Data
        </button>
    </form>
</div>
