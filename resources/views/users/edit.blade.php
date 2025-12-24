<x-layouts.app title="Edit User">
    <div class="max-w-2xl mx-auto">

        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('users.index') }}" class="text-blue-600 hover:text-blue-700 inline-flex items-center gap-1 mb-4">
                ← Kembali
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Edit User</h1>
            <p class="text-gray-600 mt-1">Edit informasi user</p>
        </div>

        <!-- Form -->
        <div class="card">
            <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium mb-2">Nama Lengkap</label>
                    <input type="text" name="name"
                        value="{{ old('name', $user->name) }}"
                        required class="input-field">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium mb-2">Email</label>
                    <input type="email" name="email"
                        value="{{ old('email', $user->email) }}"
                        required class="input-field">
                </div>

                <!-- Role -->
                <div>
                    <label class="block text-sm font-medium mb-2">Role</label>
                    <select name="role" id="role" class="input-field" required>
                        <option value="admin" {{ old('role',$user->role)=='admin'?'selected':'' }}>Admin</option>
                        <option value="viewer" {{ old('role',$user->role)=='viewer'?'selected':'' }}>Viewer</option>
                    </select>
                </div>

                <!-- Gudang -->
                <div id="gudang-wrapper" class="{{ old('role',$user->role)==='admin' ? '' : 'hidden' }}">
                    <label class="block text-sm font-medium mb-2">Gudang (khusus Admin)</label>
                    <select name="gudang_id" class="input-field">
                        <option value="">Pilih Gudang</option>
                        @foreach($gudangs as $gudang)
                            <option value="{{ $gudang->id }}"
                                {{ old('gudang_id',$user->gudang_id)==$gudang->id?'selected':'' }}>
                                {{ $gudang->nama_gudang }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Password -->
                <div class="border-t pt-6">
                    <h3 class="text-sm font-medium mb-2">Ubah Password (Opsional)</h3>
                    <input type="password" name="password" class="input-field" placeholder="Password baru">
                    <input type="password" name="password_confirmation" class="input-field mt-2" placeholder="Konfirmasi password">
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-4 border-t">
                    <button class="btn-primary">Update User</button>
                    <a href="{{ route('users.index') }}" class="btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <!-- JS -->
    <script>
        document.getElementById('role').addEventListener('change', function () {
            document.getElementById('gudang-wrapper')
                .classList.toggle('hidden', this.value !== 'admin');
        });
    </script>
</x-layouts.app>
