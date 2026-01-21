<div class="space-y-10">

    {{-- FORM --}}
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8 max-w-xl">
        <div class="mb-6">
            <h3 class="text-xl font-semibold text-gray-800">
                Tambah Akun Detail
            </h3>
            <p class="text-sm text-gray-500">
                Tambahkan akun baru ke dalam struktur chart of account
            </p>
        </div>

        @if (session()->has('success'))
            <div class="mb-5 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-6">

            {{-- Parent akun --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Kelompok Akun
                </label>
                <select wire:model="parent_id"
                        class="w-full rounded-xl border-2 border-gray-300 bg-white text-sm
                               hover:border-blue-400
                               focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition">
                    <option value="">-- pilih --</option>
                    @foreach($parents as $p)
                        <option value="{{ $p->id }}">
                            {{ $p->kode }} - {{ $p->nama }}
                        </option>
                    @endforeach
                </select>
                @error('parent_id')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nama akun --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Nama Akun
                </label>
                <input
                    wire:model="nama"
                    type="text"
                    class="w-full rounded-xl border-2 border-gray-300 bg-white text-sm
                           hover:border-blue-400
                           focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition"
                    placeholder="Masukkan nama akun"
                />
                @error('nama')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Preview kode --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Kode Akun (Otomatis)
                </label>
                <input
                    type="text"
                    class="w-full rounded-xl border-2 border-dashed border-gray-300
                           bg-gray-50 text-sm text-gray-600 cursor-not-allowed"
                    value="{{ $previewKode ?? '-' }}"
                    readonly
                />
            </div>

            <div class="pt-2">
                <button
                    wire:click="save"
                    class="inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white
                           hover:bg-blue-700
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition">
                    Simpan Akun
                </button>
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
