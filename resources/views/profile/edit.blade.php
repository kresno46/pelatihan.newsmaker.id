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
    @php
        $kantorCabang = [
            'RFB' => [
                'Palembang',
                'Balikpapan',
                'Solo',
                'Jakarta DBS Tower',
                'Jakarta AXA Tower',
                'Medan',
                'Semarang',
                'Surabaya Pakuwon',
                'Surabaya Ciputra',
                'Pekanbaru',
                'Bandung',
                'Yogyakarta',
            ],
            'SGB' => ['Jakarta', 'Semarang', 'Makassar'],
            'KPF' => ['Jakarta', 'Yogyakarta', 'Bali', 'Makassar', 'Bandung', 'Semarang'],
            'EWF' => [
                'SCC Jakarta',
                'Cyber 2 Jakarta',
                'Surabaya Trilium',
                'Manado',
                'Semarang',
                'Surabaya Praxis',
                'Cirebon',
            ],
            'BPF' => [
                'Equity Tower Jakarta',
                'Jambi',
                'Jakarta - Pacific Place Mall',
                'Pontianak',
                'Malang',
                'Surabaya',
                'Medan',
                'Bandung',
                'Pekanbaru',
                'Banjarmasin',
                'Bandar Lampung',
                'Semarang',
            ],
        ];
        $selectedCabang = old('cabang', $user->cabang ?? '');
    @endphp

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set batas maksimum tanggal lahir (minimal 18 tahun)
            const today = new Date();
            const year = today.getFullYear() - 18;
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            document.getElementById('tanggal_lahir').max = `${year}-${month}-${day}`;

            const dataCabang = @json($kantorCabang);
            const selectedCabang = @json($selectedCabang);
            const roleSelect = document.getElementById('role');
            const cabangContainer = document.getElementById('cabang-container');
            const cabangSelect = document.getElementById('cabang');

            function updateCabangOptions(roleText) {
                cabangSelect.innerHTML = '<option value="">-- Pilih Kantor Cabang --</option>';
                let match = roleText.match(/\((.*?)\)/); // Ambil teks dalam kurung
                if (match && dataCabang[match[1]]) {
                    dataCabang[match[1]].forEach(c => {
                        const opt = document.createElement('option');
                        opt.value = c;
                        opt.textContent = c;
                        if (c === selectedCabang) opt.selected = true;
                        cabangSelect.appendChild(opt);
                    });
                    cabangContainer.style.display = 'block';
                } else {
                    cabangContainer.style.display = 'none';
                }
            }

            if (roleSelect && roleSelect.value) {
                updateCabangOptions(roleSelect.value); // Panggil langsung saat load
            }

            // Tetap tambahkan event listener kalau suatu saat role bisa diubah
            roleSelect?.addEventListener('change', function() {
                updateCabangOptions(this.value);
            });
        });

        @if (session('error'))
            function closeIncompleteModal() {
                document.getElementById('modalIncompleteProfile').classList.add('hidden');
            }
            window.addEventListener('DOMContentLoaded', () => {
                document.getElementById('modalIncompleteProfile')?.classList.remove('hidden');
            });
        @endif
    </script>
@endsection
