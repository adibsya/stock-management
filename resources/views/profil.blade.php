<x-layouts.app :title="'Profil Saya'" :header="'Profil Pengguna'">
    @include('components.sweetalert-script')

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">

        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">

            <!-- HEADER -->
            <div class="px-6 py-8 sm:px-10 border-b border-gray-100 dark:border-gray-800">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
                    Profil Pengguna
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Kelola informasi akun dan keamanan Anda
                </p>
            </div>

            <!-- CONTENT -->
            <div class="p-6 sm:p-10 grid grid-cols-1 md:grid-cols-3 gap-10">

                <!-- FOTO PROFIL -->
                <form method="POST"
                      action="{{ route('profil.foto') }}"
                      enctype="multipart/form-data"
                      class="flex flex-col items-center md:items-start">
                    @csrf

                    <div class="relative">
                        <div class="w-36 h-36 sm:w-40 sm:h-40 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-700">
                            <img
                                src="{{ auth()->user()->foto
                                    ? asset('storage/' . auth()->user()->foto)
                                    : asset('logo.png') }}"
                                class="object-cover w-full h-full"
                                alt="Foto Profil">
                        </div>

                        <!-- ICON PENSIL -->
                        <label for="foto"
                               class="absolute -bottom-2 -right-2 w-11 h-11 rounded-full
                                      bg-sky-600 flex items-center justify-center
                                      cursor-pointer shadow-lg
                                      ring-4 ring-white dark:ring-gray-900
                                      hover:bg-sky-700 transition"
                               title="Ubah Foto Profil">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-5 h-5 text-white"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13zm-6 6h6" />
                            </svg>
                        </label>

                        <input id="foto"
                               type="file"
                               name="foto"
                               accept="image/*"
                               class="hidden"
                               onchange="this.form.submit()">
                    </div>

                    <p class="mt-4 text-sm text-gray-500 dark:text-gray-400 text-center md:text-left">
                        Klik ikon pensil untuk mengganti foto profil
                    </p>
                </form>

                <!-- DATA USER -->
                <div class="md:col-span-2 space-y-6">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                        <div>
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Nama</label>
                            <input type="text"
                                   value="{{ auth()->user()->name }}"
                                   disabled
                                   class="mt-2 w-full rounded-xl border border-gray-300 dark:border-gray-700
                                          bg-gray-50 dark:bg-gray-800 px-4 py-3
                                          text-gray-900 dark:text-gray-100">
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Email</label>
                            <input type="email"
                                   value="{{ auth()->user()->email }}"
                                   disabled
                                   class="mt-2 w-full rounded-xl border border-gray-300 dark:border-gray-700
                                          bg-gray-50 dark:bg-gray-800 px-4 py-3
                                          text-gray-900 dark:text-gray-100">
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Role</label>
                            <input type="text"
                                   value="{{ auth()->user()->role_label }}"
                                   disabled
                                   class="mt-2 w-full rounded-xl border border-gray-300 dark:border-gray-700
                                          bg-gray-50 dark:bg-gray-800 px-4 py-3
                                          text-gray-900 dark:text-gray-100">
                        </div>

                    </div>

                    <!-- ACTION -->
                    <div class="pt-6 border-t border-gray-100 dark:border-gray-800 flex justify-end">
                        <button onclick="openPasswordModal()"
                                class="inline-flex items-center gap-2 px-6 py-3 rounded-xl
                                       bg-sky-600 text-white font-medium shadow-sm
                                       hover:bg-sky-700 transition">
                            Ubah Password
                        </button>
                    </div>

                </div>

            </div>
        </div>

    </div>

    <!-- MODAL UBAH PASSWORD -->
    <div id="passwordModal" class="fixed inset-0 z-50 hidden items-center justify-center">

        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"
             onclick="closePasswordModal()"></div>

        <div class="relative bg-white dark:bg-gray-900 w-full max-w-md
                    rounded-2xl shadow-xl p-6 sm:p-8 mx-4">

            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Ubah Password
                </h3>
                <button onclick="closePasswordModal()"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    ✕
                </button>
            </div>

            <form method="POST" action="{{ route('profil.password.update') }}" class="space-y-5">
    @csrf
    @method('PUT')

    <div>
        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">
            Password Lama
        </label>
        <input type="password" name="current_password" required
               class="mt-2 w-full rounded-xl border border-gray-300 dark:border-gray-700
                      bg-gray-50 dark:bg-gray-800 px-4 py-3">
    </div>

    <div>
        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">
            Password Baru
        </label>
        <input type="password" name="new_password" required
               class="mt-2 w-full rounded-xl border border-gray-300 dark:border-gray-700
                      bg-gray-50 dark:bg-gray-800 px-4 py-3">
    </div>

    <div>
        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">
            Konfirmasi Password Baru
        </label>
        <input type="password" name="new_password_confirmation" required
               class="mt-2 w-full rounded-xl border border-gray-300 dark:border-gray-700
                      bg-gray-50 dark:bg-gray-800 px-4 py-3">
    </div>

    <div class="pt-4 flex justify-end gap-3">
        <button type="button"
                onclick="closePasswordModal()"
                class="px-5 py-2.5 rounded-xl border border-gray-300
                       text-gray-700 dark:text-gray-300">
            Batal
        </button>
        <button type="submit"
                class="px-5 py-2.5 rounded-xl bg-sky-600 text-white hover:bg-sky-700">
            Simpan
        </button>
    </div>
</form>


        </div>
    </div>

    <script>
        function openPasswordModal() {
            const modal = document.getElementById('passwordModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                Swal.fire({
                    icon: 'info',
                    title: 'Ubah Password',
                    text: 'Silakan masukkan password lama dan password baru Anda.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }, 200);
        }

        function closePasswordModal() {
            const modal = document.getElementById('passwordModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // SweetAlert untuk sukses ganti password
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: @json(session('success')),
            timer: 2000,
            showConfirmButton: false
        });
        @endif

        // SweetAlert untuk error ganti password
        @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: @json($errors->first()),
        });
        @endif
    </script>

</x-layouts.app>
