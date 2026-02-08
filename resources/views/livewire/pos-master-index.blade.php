<div class="space-y-10">

    {{-- FORM: Tambah Akun Detail --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden max-w-xl">
        {{-- Header dengan accent --}}
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-8 py-6">
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm">
                    <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white tracking-tight">
                        Tambah Akun Detail
                    </h3>
                    <p class="text-sm text-indigo-100 mt-0.5">
                        Tambahkan akun baru ke dalam chart of account
                    </p>
                </div>
            </div>
        </div>

        <div class="p-8">
            @if (session()->has('success'))
                <div class="mb-6 flex items-center gap-3 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-800">
                    <svg class="h-5 w-5 flex-shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="space-y-6">
                {{-- Kelompok Akun --}}
                <div class="group">
                    <label class="mb-2 flex items-center gap-2 text-sm font-semibold text-gray-700">
                        <svg class="h-4 w-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        Kelompok Akun
                    </label>
                    <select wire:model="parent_id"
                            class="w-full rounded-xl border-2 border-gray-200 bg-gray-50/80 py-3 pl-4 pr-10 text-sm text-gray-800
                                   placeholder-gray-400
                                   hover:border-indigo-300 hover:bg-white
                                   focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/20
                                   transition duration-200">
                        <option value="">— Pilih kelompok akun —</option>
                        @foreach($parents as $p)
                            <option value="{{ $p->id }}">
                                {{ $p->kode }} — {{ $p->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
                            <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Nama Akun --}}
                <div class="group">
                    <label class="mb-2 flex items-center gap-2 text-sm font-semibold text-gray-700">
                        <svg class="h-4 w-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        Nama Akun
                    </label>
                    <input
                        wire:model="nama"
                        type="text"
                        class="w-full rounded-xl border-2 border-gray-200 bg-gray-50/80 py-3 px-4 text-sm text-gray-800
                               placeholder-gray-400
                               hover:border-indigo-300 hover:bg-white
                               focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/20
                               transition duration-200"
                        placeholder="Contoh: Kas Kecil, Bank BCA, Piutang Usaha"
                    />
                    @error('nama')
                        <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
                            <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Kode Akun (Otomatis) --}}
                <div class="group">
                    <label class="mb-2 flex items-center gap-2 text-sm font-semibold text-gray-700">
                        <svg class="h-4 w-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                        </svg>
                        Kode Akun
                        <span class="rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-700">Otomatis</span>
                    </label>
                    <div class="flex items-center gap-2 rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 py-3 px-4">
                        <span class="font-mono text-sm font-semibold text-gray-600 tabular-nums">
                            {{ $previewKode ?? '—' }}
                        </span>
                        @if($previewKode)
                            <span class="text-xs text-gray-400">(akan digunakan saat disimpan)</span>
                        @endif
                    </div>
                </div>

                <div class="pt-2">
                    <button
                        wire:click="save"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-6 py-3.5 text-sm font-semibold text-white shadow-md
                               hover:bg-indigo-700 hover:shadow-lg
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2
                               active:scale-[0.99]
                               transition duration-200">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Akun
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL --}}
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8">
        <div class="mb-5">
            <h3 class="text-xl font-semibold text-gray-800">
                Daftar Akun
            </h3>
            <p class="text-sm text-gray-500">
                Struktur akun yang digunakan untuk jurnal dan laporan keuangan
            </p>
        </div>

        <div class="overflow-x-auto rounded-xl border border-gray-200">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">Kode</th>
                        <th class="px-4 py-3 text-left font-medium">Nama</th>
                        <th class="px-4 py-3 text-left font-medium">Kategori</th>
                        <th class="px-4 py-3 text-left font-medium">Sub</th>
                        <th class="px-4 py-3 text-center font-medium">Normal</th>
                        <th class="px-4 py-3 text-center font-medium">Level</th>
                        <th class="px-4 py-3 text-center font-medium">Tipe</th>
                        <th class="px-4 py-3 text-left font-medium">Induk</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @foreach($pos as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-mono text-gray-800">
                                {{ $item->kode }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-800">
                                {{ $item->nama }}
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ $item->kategori ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ $item->sub_kategori ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-center font-semibold">
                                {{ $item->normal_saldo }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                {{ $item->level }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium
                                    {{ $item->level >= 2
                                        ? 'bg-emerald-100 text-emerald-700'
                                        : 'bg-gray-200 text-gray-700' }}">
                                    {{ $item->level >= 2 ? 'Posting' : 'Header' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                {{ optional($item->parent)->nama ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
