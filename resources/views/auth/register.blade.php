@extends('layouts.guest')

@section('namePage', 'Daftar')

@section('content')
    <form method="POST" action="{{ route('register') }}" class="w-full space-y-5 text-gray-900 dark:text-gray-100">
        @csrf

        <!-- Nama Lengkap -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" class="dark:text-gray-200" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
                placeholder="John Doe" autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 dark:text-red-400" />
        </div>

        <!-- Tempat dan Tanggal Lahir -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Tempat Lahir -->
            <div>
                <x-input-label for="tempat_lahir" :value="__('Tempat Lahir')" class="dark:text-gray-200" />
                <x-text-input id="tempat_lahir" class="block mt-1 w-full" type="text" name="tempat_lahir"
                    :value="old('tempat_lahir')" placeholder="Jakarta" required />
                <x-input-error :messages="$errors->get('tempat_lahir')" class="mt-2 dark:text-red-400" />
            </div>

            <!-- Tanggal Lahir -->
            <div>
                <x-input-label for="tanggal_lahir" :value="__('Tanggal Lahir')" class="dark:text-gray-200" />
                <x-text-input id="tanggal_lahir" class="block mt-1 w-full" type="date" name="tanggal_lahir"
                    :value="old('tanggal_lahir')" required />
                <x-input-error :messages="$errors->get('tanggal_lahir')" class="mt-2 dark:text-red-400" />
            </div>
        </div>

        <!-- Jenis Kelamin -->
        <div>
            <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" class="dark:text-gray-200" />
            <select id="jenis_kelamin" name="jenis_kelamin" required
                class="block mt-1 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                <option value="" disabled selected>Pilih jenis kelamin</option>
                <option value="Pria" {{ old('jenis_kelamin') == 'Pria' ? 'selected' : '' }}>Pria</option>
                <option value="Wanita" {{ old('jenis_kelamin') == 'Wanita' ? 'selected' : '' }}>Wanita</option>
            </select>
            <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-2 dark:text-red-400" />
        </div>

        <!-- Alamat Rumah -->
        <div>
            <x-input-label for="alamat" :value="__('Alamat Rumah')" class="dark:text-gray-200" />
            <textarea id="alamat" name="alamat" rows="3"
                class="block mt-1 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 shadow-sm"
                required>{{ old('alamat') }}</textarea>
            <x-input-error :messages="$errors->get('alamat')" class="mt-2 dark:text-red-400" />
        </div>

        <!-- No Handphone -->
        <div>
            <x-input-label for="no_tlp" :value="__('No Handphone')" class="dark:text-gray-200" />
            <x-text-input id="no_tlp" class="block mt-1 w-full" type="text" name="no_tlp" :value="old('no_tlp')" required
                placeholder="081234567890" />
            <x-input-error :messages="$errors->get('no_tlp')" class="mt-2 dark:text-red-400" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="dark:text-gray-200" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                placeholder="johndoe@example.com" autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 dark:text-red-400" />
        </div>

        <!-- Pekerjaan -->
        <div>
            <x-input-label for="pekerjaan" :value="__('Pekerjaan')" class="dark:text-gray-200" />

            <select id="pekerjaan" name="pekerjaan" required
                class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                <option value="">-- Pilih Pekerjaan --</option>
                <option value="Pelajar/Mahasiswa" {{ old('pekerjaan') == 'Pelajar/Mahasiswa' ? 'selected' : '' }}>
                    Pelajar/Mahasiswa</option>
                <option value="PNS" {{ old('pekerjaan') == 'PNS' ? 'selected' : '' }}>PNS</option>
                <option value="TNI/Polri" {{ old('pekerjaan') == 'TNI/Polri' ? 'selected' : '' }}>TNI/Polri</option>
                <option value="Pegawai Negeri" {{ old('pekerjaan') == 'Pegawai Negeri' ? 'selected' : '' }}>Pegawai Negeri
                </option>
                <option value="Karyawan Swasta" {{ old('pekerjaan') == 'Karyawan Swasta' ? 'selected' : '' }}>Karyawan
                    Swasta</option>
                <option value="Wiraswasta" {{ old('pekerjaan') == 'Wiraswasta' ? 'selected' : '' }}>Wiraswasta</option>
                <option value="Petani" {{ old('pekerjaan') == 'Petani' ? 'selected' : '' }}>Petani</option>
                <option value="Peternak" {{ old('pekerjaan') == 'Peternak' ? 'selected' : '' }}>Peternak</option>
                <option value="Nelayan" {{ old('pekerjaan') == 'Nelayan' ? 'selected' : '' }}>Nelayan</option>
                <option value="Buruh" {{ old('pekerjaan') == 'Buruh' ? 'selected' : '' }}>Buruh</option>
                <option value="Pensiunan" {{ old('pekerjaan') == 'Pensiunan' ? 'selected' : '' }}>Pensiunan</option>
                <option value="Ibu Rumah Tangga" {{ old('pekerjaan') == 'Ibu Rumah Tangga' ? 'selected' : '' }}>Ibu Rumah
                    Tangga</option>
                <option value="Dokter" {{ old('pekerjaan') == 'Dokter' ? 'selected' : '' }}>Dokter</option>
                <option value="Perawat" {{ old('pekerjaan') == 'Perawat' ? 'selected' : '' }}>Perawat</option>
                <option value="Guru/Dosen" {{ old('pekerjaan') == 'Guru/Dosen' ? 'selected' : '' }}>Guru/Dosen</option>
                <option value="Sopir" {{ old('pekerjaan') == 'Sopir' ? 'selected' : '' }}>Sopir</option>
                <option value="Pengacara" {{ old('pekerjaan') == 'Pengacara' ? 'selected' : '' }}>Pengacara</option>
                <option value="Arsitek" {{ old('pekerjaan') == 'Arsitek' ? 'selected' : '' }}>Arsitek</option>
                <option value="Seniman/Artis" {{ old('pekerjaan') == 'Seniman/Artis' ? 'selected' : '' }}>Seniman/Artis
                </option>
                <option value="Programmer" {{ old('pekerjaan') == 'Programmer' ? 'selected' : '' }}>Programmer</option>
                <option value="Lainnya" {{ old('pekerjaan') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>

            <x-input-error :messages="$errors->get('pekerjaan')" class="mt-2 dark:text-red-400" />
        </div>


        <!-- Kata Sandi -->
        <div>
            <x-input-label for="password" :value="__('Kata Sandi')" class="dark:text-gray-200" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 dark:text-red-400" />
        </div>

        <!-- Konfirmasi Kata Sandi -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi')" class="dark:text-gray-200" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 dark:text-red-400" />
        </div>

        <!-- Tombol Daftar -->
        <div class="flex items-center justify-end">
            <x-primary-button class="w-full">
                Daftar
            </x-primary-button>
        </div>

        <!-- Tautan Login -->
        <div>
            @if (Route::has('login'))
                <p class="text-sm text-center text-gray-600 dark:text-gray-400">
                    Sudah punya akun?
                    <a href="{{ route('login') }}"
                        class="underline text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-100">
                        Masuk
                    </a>
                </p>
            @endif
        </div>
    </form>

    <script>
        // Menghitung tanggal maksimum (hari ini - 18 tahun)
        const today = new Date();
        const year = today.getFullYear() - 18;
        const month = (today.getMonth() + 1).toString().padStart(2, '0'); // bulan 2 digit
        const day = today.getDate().toString().padStart(2, '0');

        const maxDate = `${year}-${month}-${day}`;

        document.getElementById('tanggal_lahir').max = maxDate;
    </script>
@endsection
