{{-- Update Informasi Profil --}}
<section class="space-y-3">
    <header class="w-full bg-white dark:bg-gray-800 shadow rounded-lg p-4 sm:p-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    {{ __('Profile') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Lengkapi dan perbarui data diri Anda agar informasi selalu akurat.') }}
                </p>
            </div>

            @if (session('status') === 'profile-updated')
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                    class="text-xs bg-green-100 dark:bg-green-200 text-green-800 py-1 px-3 rounded-lg">
                    {{ __('Profil berhasil diperbarui.') }}
                </div>
            @endif
        </div>
    </header>

    <div class="w-full bg-white dark:bg-gray-800 shadow rounded-lg p-4 sm:p-8">
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

            {{-- Nama Lengkap --}}
            <div>
                <x-input-label-append for="name" :value="__('Nama Lengkap')" :append="empty($user->name) ? '<span class=\'text-red-500\'>*</span>' : ''" />
                <x-text-input id="name" type="text" name="name" class="block mt-1 w-full"
                    :value="old('name', $user->name)" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            {{-- Email --}}
            <div class="mt-4">
                <x-input-label-append for="email" :value="__('Email')" :append="empty($user->email) ? '<span class=\'text-red-500\'>*</span>' : ''" />
                <x-text-input id="email" type="email" name="email" class="block mt-1 w-full"
                    :value="old('email', $user->email)" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            {{-- Jabatan
            <div class="mt-4">
                <x-input-label-append for="jabatan" :value="__('Jabatan')" :append="empty($user->jabatan) ? '<span class=\'text-red-500\'>*</span>' : ''" />
                <x-text-input id="jabatan" type="text" name="jabatan" class="block mt-1 w-full" readonly
                    :value="old('jabatan', $user->jabatan)" />
                <x-input-error :messages="$errors->get('jabatan')" class="mt-2" />
            </div> --}}

            {{-- Jabatan --}}
            <div class="mt-4">
                <x-input-label-append for="jabatan" :value="__('Jabatan')" :append="empty($user->jabatan) ? '<span class=\'text-red-500\'>*</span>' : ''" />
                
                <select id="jabatan" name="jabatan" class="block mt-1 w-full rounded-md shadow-sm border-gray-300
                            dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                    <option value="">-- Pilih Jabatan --</option>
                    @foreach (['BC', 'SBC', 'SBM', 'BM'] as $jabatan)
                        <option value="{{ $jabatan }}" {{ old('jabatan', $user->jabatan) === $jabatan ? 'selected' : '' }}>
                            {{ $jabatan }}
                        </option>
                    @endforeach
                </select>

                <x-input-error :messages="$errors->get('jabatan')" class="mt-2" />
            </div>

            {{-- Jenis Kelamin --}}
            @php
                $jenisKelaminList = ['Pria', 'Wanita'];
                $selectedJK = old('jenis_kelamin', $user->jenis_kelamin ?? '');
            @endphp
            <div class="mt-4">
                <x-input-label-append for="jenis_kelamin" :value="__('Jenis Kelamin')" :append="empty($user->jenis_kelamin) ? '<span class=\'text-red-500\'>*</span>' : ''" />
                <select id="jenis_kelamin" name="jenis_kelamin"
                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300
                            dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    @foreach ($jenisKelaminList as $jk)
                        <option value="{{ $jk }}" {{ $selectedJK == $jk ? 'selected' : '' }}>
                            {{ $jk }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-2" />
            </div>

            {{-- Tempat Lahir --}}
            <div class="mt-4">
                <x-input-label-append for="tempat_lahir" :value="__('Tempat Lahir')" :append="empty($user->tempat_lahir) ? '<span class=\'text-red-500\'>*</span>' : ''" />
                <x-text-input id="tempat_lahir" type="text" name="tempat_lahir" class="block mt-1 w-full"
                    :value="old('tempat_lahir', $user->tempat_lahir)" />
                <x-input-error :messages="$errors->get('tempat_lahir')" class="mt-2" />
            </div>

            {{-- Tanggal Lahir --}}
            <div class="mt-4">
                <x-input-label-append for="tanggal_lahir" :value="__('Tanggal Lahir')" :append="empty($user->tanggal_lahir) ? '<span class=\'text-red-500\'>*</span>' : ''" />
                <x-text-input id="tanggal_lahir" type="date" name="tanggal_lahir" class="block mt-1 w-full"
                    :value="old(
                        'tanggal_lahir',
                        $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->format('Y-m-d') : '',
                    )" />
                <x-input-error :messages="$errors->get('tanggal_lahir')" class="mt-2" />
            </div>

            {{-- Warga Negara --}}
            {{-- <div class="mt-4">
                <x-input-label-append for="warga_negara" :value="__('Warga Negara')" :append="empty($user->warga_negara) ? '<span class=\'text-red-500\'>*</span>' : ''" />
                <x-text-input id="warga_negara" type="text" name="warga_negara" class="block mt-1 w-full"
                    :value="old('warga_negara', $user->warga_negara)" />
                <x-input-error :messages="$errors->get('warga_negara')" class="mt-2" />
            </div> --}}

            {{-- Alamat --}}
            <div class="mt-4">
                <x-input-label-append for="alamat" :value="__('Kota')" :append="empty($user->alamat) ? '<span class=\'text-red-500\'>*</span>' : ''" />
                <textarea id="alamat" name="alamat"
                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300
                            dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">{{ old('alamat', $user->alamat) }}</textarea>
                <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
            </div>

            {{-- Nomor Telepon --}}
            <div class="mt-4">
                <x-input-label-append for="no_tlp" :value="__('Nomor Telepon')" :append="empty($user->no_tlp) ? '<span class=\'text-red-500\'>*</span>' : ''" />
                <x-text-input id="no_tlp" type="text" name="no_tlp" class="block mt-1 w-full"
                    :value="old('no_tlp', $user->no_tlp)" />
                <x-input-error :messages="$errors->get('no_tlp')" class="mt-2" />
            </div>

            {{-- Pekerjaan --}}
            {{-- @php
                $pekerjaanList = [
                    'Pelajar/Mahasiswa',
                    'PNS',
                    'TNI/Polri',
                    'Pegawai Negeri',
                    'Karyawan Swasta',
                    'Wiraswasta',
                    'Petani',
                    'Peternak',
                    'Nelayan',
                    'Buruh',
                    'Pensiunan',
                    'Ibu Rumah Tangga',
                    'Dokter',
                    'Perawat',
                    'Guru/Dosen',
                    'Sopir',
                    'Pengacara',
                    'Arsitek',
                    'Seniman/Artis',
                    'Programmer',
                    'Lainnya',
                ];
                $selectedPekerjaan = old('pekerjaan', $user->pekerjaan ?? '');
            @endphp
            <div class="mt-4">
                <x-input-label-append for="pekerjaan" :value="__('Pekerjaan')" :append="empty($user->pekerjaan) ? '<span class=\'text-red-500\'>*</span>' : ''" />
                <select id="pekerjaan" name="pekerjaan"
                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300
                            dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                    <option value="">-- Pilih Pekerjaan --</option>
                    @foreach ($pekerjaanList as $pekerjaan)
                        <option value="{{ $pekerjaan }}" {{ $selectedPekerjaan == $pekerjaan ? 'selected' : '' }}>
                            {{ $pekerjaan }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('pekerjaan')" class="mt-2" />
            </div> --}}

            {{-- Role --}}
            @php
                $roles = [
                    'Admin' => 'Admin',
                    'Trainer (RFB)' => 'PT Rifan Financindo Berjangka',
                    'Trainer (SGB)' => 'PT Solid Gold Berjangka',
                    'Trainer (KPF)' => 'PT Kontak Perkasa Futures',
                    'Trainer (BPF)' => 'PT Best Profit Futures',
                    'Trainer (EWF)' => 'PT Equityworld Futures',
                ];
                $selectedRole = old('role', $user->role ?? '');
            @endphp

            <div class="mt-4">
                <x-input-label for="role" :value="__('Perusahaan')" />
                <select id="role" name="role"
                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300
               dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
                    disabled>
                    <option value="">-- Pilih Perusahaan --</option>
                    @foreach ($roles as $value => $label)
                        <option value="{{ $value }}" {{ $selectedRole == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>


            {{-- Cabang --}}
            <div class="mt-4">
                <x-input-label-append for="cabang" :value="__('Cabang')" />
                <select id="cabang" name="cabang"
                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300
                            dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                    <option value="">-- Pilih Cabang --</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch }}"
                            {{ old('cabang', $user->cabang) == $branch ? 'selected' : '' }}>
                            {{ $branch }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('cabang')" class="mt-2" />
            </div>

            <x-missing-fields-alert :user="$user" />

            {{-- Tombol Simpan --}}
            <div class="flex items-center justify-end mt-10 gap-5">
                <x-primary-button class="ml-4">
                    {{ __('Simpan Perubahan') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</section>
