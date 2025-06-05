<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Hapus Akun') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Setelah akun Anda dihapus, semua data Anda akan hilang secara permanen. Mohon pastikan kembali sebelum melanjutkan.') }}
        </p>
    </header>

    <!-- Trigger modal -->
    <button type="button"
        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition"
        onclick="document.getElementById('confirm-delete-modal').classList.remove('hidden')">
        {{ __('Hapus Akun') }}
    </button>

    <!-- Modal -->
    <div id="confirm-delete-modal"
        class="hidden fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow-md w-full max-w-md">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Konfirmasi Hapus Akun') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Masukkan password Anda untuk konfirmasi penghapusan akun.') }}
            </p>

            <form method="POST" action="{{ route('profile.destroy') }}" class="mt-4">
                @csrf
                @method('DELETE')

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Password') }}
                    </label>
                    <input id="password" name="password" type="password"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 dark:bg-gray-900 dark:text-gray-100"
                        required placeholder="{{ __('Password') }}">
                    @error('password')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-6 flex justify-end gap-4">
                    <button type="button"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition"
                        onclick="document.getElementById('confirm-delete-modal').classList.add('hidden')">
                        {{ __('Batal') }}
                    </button>

                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                        {{ __('Hapus Akun') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
