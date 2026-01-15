<div class="space-y-8">

    {{-- FORM --}}
    <div class="bg-white rounded-xl shadow-sm border p-6 max-w-xl">
        <h3 class="text-lg font-semibold mb-6 text-gray-800">
            Tambah Akun (Detail)
        </h3>

        @if (session()->has('success'))
            <div class="mb-4 rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-5">

            {{-- Parent akun --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Kelompok Akun
                </label>
                <select wire:model="parent_id"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
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
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Akun
                </label>
                <input
                    wire:model="nama"
                    type="text"
                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    placeholder="contoh: Kas Kecil"
                />
                @error('nama')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Preview kode --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Kode Akun (Otomatis)
                </label>
                <input
                    type="text"
                    class="w-full rounded-lg border-gray-200 bg-gray-100 text-gray-600 cursor-not-allowed"
                    value="{{ $previewKode ?? '-' }}"
                    readonly
                />
            </div>

            <div class="pt-2">
                <button
                    wire:click="save"
                    class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition">
                    Simpan Akun
                </button>
            </div>

        </div>
    </div>

    {{-- TABEL --}}
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">
            Daftar Akun
        </h3>

        <div class="overflow-x-auto rounded-lg border">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-3 py-2 text-left font-medium">Kode</th>
                        <th class="px-3 py-2 text-left font-medium">Nama</th>
                        <th class="px-3 py-2 text-left font-medium">Kategori</th>
                        <th class="px-3 py-2 text-left font-medium">Sub Kategori</th>
                        <th class="px-3 py-2 text-center font-medium">Normal</th>
                        <th class="px-3 py-2 text-center font-medium">Level</th>
                        <th class="px-3 py-2 text-center font-medium">Tipe</th>
                        <th class="px-3 py-2 text-left font-medium">Induk</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @foreach($pos as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 font-mono">{{ $item->kode }}</td>
                            <td class="px-3 py-2">{{ $item->nama }}</td>
                            <td class="px-3 py-2">{{ $item->kategori ?? '-' }}</td>
                            <td class="px-3 py-2">{{ $item->sub_kategori ?? '-' }}</td>
                            <td class="px-3 py-2 text-center">
                                {{ $item->normal_saldo }}
                            </td>
                            <td class="px-3 py-2 text-center">
                                {{ $item->level }}
                            </td>
                            <td class="px-3 py-2 text-center">
                                <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                                    {{ $item->level >= 2 
                                        ? 'bg-green-100 text-green-700' 
                                        : 'bg-gray-200 text-gray-700' }}">
                                    {{ $item->level >= 2 ? 'Posting' : 'Header' }}
                                </span>
                            </td>
                            <td class="px-3 py-2">
                                {{ optional($item->parent)->nama ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
