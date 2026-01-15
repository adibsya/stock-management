<div>
    @php
        $showGudang = $showGudang ?? false;
    @endphp

    <!-- Header Actions -->
    <div class="card mb-6">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex flex-col md:flex-row gap-4 flex-1 w-full md:w-auto">
                <div class="relative w-full md:w-64 lg:w-80">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text"
                           wire:model.live.debounce.300ms="search"
                           placeholder="Cari user..."
                           class="input-with-icon-left">
                </div>

                <select wire:model.live="roleFilter" class="input-field w-full md:w-48">
                    <option value="">Semua Role</option>
                    <option value="super_admin">Super Admin</option>
                    <option value="admin">Admin</option>
                    <option value="viewer">Viewer</option>
                </select>
            </div>

            <a href="{{ route('users.create') }}" class="btn-primary whitespace-nowrap">
                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4v16m8-8H4"/>
                </svg>
                Tambah User
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="card-no-padding overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                <tr class="border-b border-gray-200">
                    <th class="table-header cursor-pointer" wire:click="sortBy('name')">
                        Nama
                        @if($sortBy === 'name')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th class="table-header cursor-pointer" wire:click="sortBy('email')">
                        Email
                        @if($sortBy === 'email')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>

                    <th class="table-header">Gudang</th>

                    <th class="table-header">Role</th>
                    <th class="table-header text-center">Aksi</th>
                </tr>
                </thead>

                <tbody>
                @forelse($users as $user)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <!-- Nama & Foto -->
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @if($user->foto)
                                    <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto Profil" class="w-10 h-10 rounded-full object-cover border-2 border-blue-400 shadow-sm">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="text-blue-600 font-semibold text-sm">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </span>
                                    </div>
                                @endif
                                <div>
                                    <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                    @if($user->id === auth()->id())
                                        <span class="text-xs text-blue-600">(Anda)</span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Email -->
                        <td class="px-4 py-3 text-gray-700">
                            {{ $user->email }}
                        </td>

                        <!-- Gudang -->
                        <td class="px-2 py-3 text-gray-700">
                            @if($user->role === 'admin' && $user->gudang)
                                <div class="flex flex-col">
                                    <span class="badge bg-green-100 text-green-800 w-fit">
                                        {{ $user->gudang->nama_gudang }}
                                    </span>
                                </div>
                            @elseif($user->role === 'admin')
                                <span class="text-red-500 text-xs">(Belum dipilih)</span>
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>

                        <!-- Role -->
                        <td class="px-4 py-3">
                            @if($user->role === 'super_admin')
                                <span class="badge bg-purple-100 text-purple-800">Super Admin</span>
                            @elseif($user->role === 'admin')
                                <span class="badge bg-blue-100 text-blue-800">Admin</span>
                            @else
                                <span class="badge bg-gray-100 text-gray-800">Viewer</span>
                            @endif
                        </td>

                        <!-- Aksi -->
                        <td class="px-4 py-3">
                            <div class="flex justify-center gap-2">
                                @if(auth()->user()->isSuperAdmin() && $user->id !== auth()->id())
                                    <a href="{{ route('users.edit', $user) }}" class="btn-secondary-sm">Edit</a>
                                    @if(!$user->isSuperAdmin())
                                        <button wire:click="delete({{ $user->id }})" wire:confirm="Yakin ingin menghapus user ini?" class="btn-danger-sm">Hapus</button>
                                    @endif
                                @elseif(auth()->user()->isAdmin() && !$user->isSuperAdmin() && $user->id !== auth()->id())
                                    <a href="{{ route('users.edit', $user) }}" class="btn-secondary-sm">Edit</a>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $showGudang ? 5 : 4 }}"
                            class="px-4 py-8 text-center text-gray-500">
                            Tidak ada user ditemukan
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    </div>
</div>
