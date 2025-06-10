{{-- resources/views/profile/partials/update-password-form.blade.php --}}
<section class="space-y-3">
    <header class="w-full bg-white dark:bg-gray-800 shadow rounded-lg p-4 sm:p-8">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div>
                <h2 class="text-xl font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Reset Password') }}
                </h2>

                <p class="mt-1 text-base text-gray-600 dark:text-gray-400">
                    {{ __('Atur ulang kata sandi akun Anda untuk menjaga keamanan.') }}
                </p>
            </div>

            @if (session('status') === 'password-updated')
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 10000)"
                    class="text-sm bg-green-300 dark:text-green-400 py-2 px-3 rounded-xl">
                    <span class="text-green-800">{{ __('Kata sandi berhasil diperbarui.') }}</span>
                </div>
            @endif
        </div>
    </header>

    <div class="w-full p-4 sm:p-8 bg-white dark:bg-gray-800 shadow rounded-lg">
        <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Password Saat Ini --}}
            <div>
                <x-input-label for="current_password" :value="__('Password Saat Ini')" />
                <x-text-input id="current_password" name="current_password" type="password" class="mt-1 block w-full"
                    autocomplete="current-password" />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>

            {{-- Password Baru --}}
            <div>
                <x-input-label for="password" :value="__('Password Baru')" />
                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full"
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            {{-- Konfirmasi Password Baru --}}
            <div>
                <x-input-label for="password_confirmation" :value="__('Konfirmasi Password Baru')" />
                <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                    class="mt-1 block w-full" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>

            {{-- Tombol --}}
            <div class="flex items-center justify-end mt-10">
                @if (session('success') === 'password-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-green-600 dark:text-green-400">{{ __('Password berhasil diperbarui.') }}</p>
                @endif

                <x-primary-button>{{ __('Update Password') }}</x-primary-button>
            </div>
        </form>
    </div>
</section>
