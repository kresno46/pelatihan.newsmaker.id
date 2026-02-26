{{-- Update Informasi Profil --}}
<section class="space-y-4">
    <div
        class="w-full rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:p-8">
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

            <div class="grid gap-4 sm:grid-cols-2">
                {{-- Nama Lengkap --}}
                <div class="sm:col-span-2">
                    <x-input-label-append for="name" :value="__('Nama Lengkap')" :append="empty($user->name) ? '<span class=\'text-red-500\'>*</span>' : ''" />
                    <x-text-input id="name" type="text" name="name" class="block mt-1 w-full" readonly
                        :value="old('name', $user->name)" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 italic">
                        ~ Nama lengkap tidak dapat diubah. Jika ada kesalahan, hubungi administrator. ~
                    </p>
                </div>

                {{-- Email --}}
                <div>
                    <x-input-label-append for="email" :value="__('Email')" :append="empty($user->email) ? '<span class=\'text-red-500\'>*</span>' : ''" />
                    <x-text-input id="email" type="email" name="email" class="block mt-1 w-full"
                        :value="old('email', $user->email)" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                {{-- Nomor Telepon --}}
                <div>
                    <x-input-label-append for="no_tlp" :value="__('Nomor Telepon')" :append="empty($user->no_tlp) ? '<span class=\'text-red-500\'>*</span>' : ''" />
                    <x-text-input id="no_tlp" type="text" name="no_tlp" class="block mt-1 w-full"
                        :value="old('no_tlp', $user->no_tlp)" />
                    <x-input-error :messages="$errors->get('no_tlp')" class="mt-2" />
                </div>

                {{-- Jabatan --}}
                <div>
                    <x-input-label-append for="jabatan" :value="__('Jabatan')" :append="empty($user->jabatan) ? '<span class=\'text-red-500\'>*</span>' : ''" />

                    @if (auth()->user()->jabatan)
                        @php
                            $jabatanMap = [
                                'Admin' => 'Administrator',
                                'Owner' => 'Owner',
                                'CEO' => 'Chief Executive Officer',
                                'CBO' => 'Chief Business Officer',
                                'BrM' => 'Branch Manager',
                                'VBM' => 'Vice Business Manager',
                                'SVBM' => 'Senior Vice Business Manager',
                                'SEM' => 'Senior Executive Manager',
                                'EM' => 'Executive Manager',
                                'SBM' => 'Senior Business Manager',
                                'BsM' => 'Business Manager',
                                'SBC' => 'Senior Business Consultant',
                                'BC' => 'Business Consultant',
                            ];

                            $jabatanValue = $user->jabatan;
                            $jabatanText = $jabatanMap[$jabatanValue] ?? $jabatanValue;
                        @endphp

                        <x-text-input id="jabatan" type="text" class="block mt-1 w-full" readonly
                            :value="$jabatanText" />
                        <input type="hidden" name="jabatan" value="{{ $jabatanValue }}">

                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 italic">
                            ~ Jabatan tidak dapat diubah. Jika ada kesalahan, hubungi administrator. ~
                        </p>
                    @else
                        @php
                            $jabatanMap = [
                                'Admin' => 'Administrator',
                                'Owner' => 'Owner',
                                'CEO' => 'Chief Executive Officer',
                                'CBO' => 'Chief Business Officer',
                                'BrM' => 'Branch Manager',
                                'VBM' => 'Vice Business Manager',
                                'SVBM' => 'Senior Vice Business Manager',
                                'SEM' => 'Senior Executive Manager',
                                'EM' => 'Executive Manager',
                                'SBM' => 'Senior Business Manager',
                                'BsM' => 'Business Manager',
                                'SBC' => 'Senior Business Consultant',
                                'BC' => 'Business Consultant',
                            ];
                            $pilihan = ['BC', 'SBC', 'BsM', 'SBM', 'EM', 'SEM', 'VBM', 'BrM'];
                        @endphp

                        <select id="jabatan" name="jabatan"
                            class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600">
                            <option value="">-- Pilih Jabatan --</option>
                            @foreach ($pilihan as $kode)
                                <option value="{{ $kode }}"
                                    {{ old('jabatan', $user->jabatan) === $kode ? 'selected' : '' }}>
                                    {{ $jabatanMap[$kode] }}
                                </option>
                            @endforeach
                        </select>
                    @endif

                    <x-input-error :messages="$errors->get('jabatan')" class="mt-2" />
                </div>

                {{-- Jenis Kelamin --}}
                @php
                    $jenisKelaminList = ['Pria', 'Wanita'];
                    $selectedJK = old('jenis_kelamin', $user->jenis_kelamin ?? '');
                @endphp
                <div>
                    <x-input-label-append for="jenis_kelamin" :value="__('Jenis Kelamin')" :append="empty($user->jenis_kelamin) ? '<span class=\'text-red-500\'>*</span>' : ''" />
                    <select id="jenis_kelamin" name="jenis_kelamin"
                        class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
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
                <div>
                    <x-input-label-append for="tempat_lahir" :value="__('Tempat Lahir')" :append="empty($user->tempat_lahir) ? '<span class=\'text-red-500\'>*</span>' : ''" />
                    <x-text-input id="tempat_lahir" type="text" name="tempat_lahir" class="block mt-1 w-full"
                        :value="old('tempat_lahir', $user->tempat_lahir)" />
                    <x-input-error :messages="$errors->get('tempat_lahir')" class="mt-2" />
                </div>

                {{-- Tanggal Lahir --}}
                <div>
                    <x-input-label-append for="tanggal_lahir" :value="__('Tanggal Lahir')" :append="empty($user->tanggal_lahir) ? '<span class=\'text-red-500\'>*</span>' : ''" />
                    <x-text-input id="tanggal_lahir" type="date" name="tanggal_lahir" class="block mt-1 w-full"
                        :value="old(
                            'tanggal_lahir',
                            $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->format('Y-m-d') : '',
                        )" />
                    <x-input-error :messages="$errors->get('tanggal_lahir')" class="mt-2" />
                </div>

                {{-- Alamat --}}
                <div class="sm:col-span-2">
                    <x-input-label-append for="alamat" :value="__('Alamat')" :append="empty($user->alamat) ? '<span class=\'text-red-500\'>*</span>' : ''" />
                    <textarea id="alamat" name="alamat"
                        class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">{{ old('alamat', $user->alamat) }}</textarea>
                    <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                </div>

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

                <div>
                    <x-input-label for="role" :value="__('Perusahaan')" />
                    <select id="role" name="role"
                        class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
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
                <div id="cabang-container">
                    <x-input-label-append for="cabang" :value="__('Cabang')" :append="empty($user->cabang) ? '<span class=\'text-red-500\'>*</span>' : ''" />
                    <select id="cabang" name="cabang"
                        class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                        <option value="">-- Pilih Kantor Cabang --</option>
                        @foreach ($allBranches as $role => $roleBranches)
                            @foreach ($roleBranches as $branch)
                                <option value="{{ $branch }}"
                                    {{ old('cabang', $user->cabang) === $branch ? 'selected' : '' }}>
                                    {{ $branch }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('cabang')" class="mt-2" />
                </div>
            </div>

            <div class="mt-6">
                <x-missing-fields-alert :user="$user" />
            </div>

            <div class="mt-8 flex items-center justify-end gap-3 border-t border-gray-200 pt-6 dark:border-gray-700">
                <x-primary-button class="ml-0">
                    {{ __('Simpan Perubahan') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</section>
