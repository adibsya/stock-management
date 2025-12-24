<x-layouts.app title="Tambah User">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('users.index') }}" class="text-blue-600 hover:text-blue-700 inline-flex items-center gap-1 mb-4">
                ← Kembali
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Tambah User Baru</h1>
            <p class="text-gray-600 mt-1">Tambahkan akun admin atau viewer</p>
        </div>

        <div class="card">
            <form method="POST" action="{{ route('users.store') }}" class="space-y-6">
                @csrf

                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-medium mb-2">Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="input-field">
                    @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="input-field">
                    @error('email') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                </div>

                {{-- Role --}}
                <div>
                    <label class="block text-sm font-medium mb-2">Role</label>
                    <select name="role" id="role" required class="input-field">
                        <option value="">Pilih Role</option>
                        <option value="admin" {{ old('role')=='admin'?'selected':'' }}>Admin</option>
                        <option value="viewer" {{ old('role')=='viewer'?'selected':'' }}>Viewer</option>
                    </select>
                    @error('role') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                </div>

                {{-- Gudang --}}
                <div id="gudang-wrapper" class="{{ old('role')==='admin' ? '' : 'hidden' }}">
                    <label class="block text-sm font-medium mb-2">Gudang (Admin)</label>
                    <select name="gudang_id" class="input-field">
                        <option value="">Pilih Gudang</option>
                        @foreach($gudangs as $gudang)
                            <option value="{{ $gudang->id }}"
                                {{ old('gudang_id')==$gudang->id?'selected':'' }}>
                                {{ $gudang->nama_gudang }}
                            </option>
                        @endforeach
                    </select>
                    @error('gudang_id') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-medium mb-2">Password</label>
                    <input type="password" name="password" required minlength="8" class="input-field">
                </div>

                {{-- Confirm --}}
                <div>
                    <label class="block text-sm font-medium mb-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required minlength="8" class="input-field">
                </div>

                <div class="flex gap-3 pt-4 border-t">
                    <button class="btn-primary">Simpan</button>
                    <a href="{{ route('users.index') }}" class="btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('role').addEventListener('change', function () {
            document.getElementById('gudang-wrapper')
                .classList.toggle('hidden', this.value !== 'admin');
        });
    </script>
</x-layouts.app>
