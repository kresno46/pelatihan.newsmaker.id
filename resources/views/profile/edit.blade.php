@extends('layouts.app')

@section('namePage', 'Profil Saya')

@section('content')
    <div class="space-y-10">
        @include('profile.partials.update-profile-information-form')

        @include('profile.partials.update-password-form')

        {{-- @include('profile.partials.delete-user-form') --}}
    </div>

    @if (session('error'))
        <div id="modalIncompleteProfile"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm transition-opacity duration-200"
            onclick="event.target === this && closeIncompleteModal()">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md transform transition-all scale-95">
                <!-- Header -->
                <div class="px-6 py-5">
                    <h3 class="text-xl font-bold text-red-500 flex items-center gap-3">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>Profil Belum Lengkap</span>
                    </h3>
                </div>

                <!-- Body -->
                <div class="px-6 py-4 border-t border-b border-gray-200 dark:border-gray-600">
                    <p class="text-gray-700 dark:text-gray-300">
                        {{ session('error') ?? 'Silakan lengkapi profil Anda terlebih dahulu sebelum melanjutkan.' }}
                    </p>
                </div>

                <!-- Footer -->
                <div class="flex justify-end gap-3 px-6 py-4">
                    <button onclick="closeIncompleteModal()"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-md text-gray-800 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500 transition">
                        Tutup
                    </button>
                    <a href="{{ route('profile.edit') }}"
                        class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-md transition">
                        Lengkapi Profil
                    </a>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    <script>
        // Menghitung tanggal maksimum (hari ini - 18 tahun)
        const today = new Date();
        const year = today.getFullYear() - 18;
        const month = (today.getMonth() + 1).toString().padStart(2, '0'); // bulan 2 digit
        const day = today.getDate().toString().padStart(2, '0');

        const maxDate = `${year}-${month}-${day}`;

        document.getElementById('tanggal_lahir').max = maxDate;
    </script>

    @if (session('error'))
        <script>
            function closeIncompleteModal() {
                document.getElementById('modalIncompleteProfile').classList.add('hidden');
            }

            // Modal akan muncul otomatis saat halaman dimuat jika ada session error
            window.addEventListener('DOMContentLoaded', () => {
                const modal = document.getElementById('modalIncompleteProfile');
                if (modal) {
                    modal.classList.remove('hidden');
                }
            });
        </script>
    @endif
@endsection
