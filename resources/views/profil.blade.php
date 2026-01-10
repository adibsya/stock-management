<x-layouts.app :title="'Profil Saya'" :header="'Profil Pengguna'">
    <div class="max-w-xl mx-auto bg-white dark:bg-gray-800 rounded-2xl shadow p-8 mt-8">
        <form method="POST" action="{{ route('profil.foto') }}" enctype="multipart/form-data" class="mb-6 flex flex-col items-center">
            @csrf
            <div class="w-40 h-40 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center overflow-hidden mb-2 relative">
                <img src="{{ auth()->user()->foto ? asset('storage/' . auth()->user()->foto) : asset('logo.png') }}" alt="Foto Profil" class="object-cover w-full h-full">
                <label for="foto" class="absolute w-10 h-10 rounded-full bg-sky-600 flex items-center justify-center cursor-pointer" style="right:-20px; bottom:-20px; z-index:2; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13zm-6 6v-2a2 2 0 012-2h2" />
                    </svg>
                </label>
                <input id="foto" type="file" name="foto" accept="image/*" class="hidden" onchange="this.form.submit()">
            </div>
        </form>
        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Nama</label>
                <input type="text" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-3 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100" value="{{ auth()->user()->name }}" disabled>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Email</label>
                <input type="email" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-3 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100" value="{{ auth()->user()->email }}" disabled>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Role</label>
                <input type="text" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-3 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100" value="{{ auth()->user()->role_label }}" disabled>
            </div>
        </div>
        <div class="mt-8 text-center">
            <a href="#" class="inline-block px-6 py-2 rounded-lg bg-sky-600 text-white font-semibold shadow hover:bg-sky-700 transition">Ubah Password</a>
        </div>
    </div>
</x-layouts.app>
