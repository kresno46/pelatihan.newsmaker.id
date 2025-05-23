@extends('layouts.guest')

@section('namePage', 'Daftar')

@section('content')
<form method="POST" action="{{ route('register') }}" class="w-full space-y-5 text-gray-900 dark:text-gray-100">
    @csrf

    <!-- Nama -->
    <div>
        <x-input-label for="name" :value="__('Nama')" class="dark:text-gray-200" />
        <x-text-input id="name" class="block mt-1 w-full
            border border-gray-300 dark:border-gray-600
            bg-white dark:bg-gray-800
            text-gray-900 dark:text-gray-100
            placeholder-gray-400 dark:placeholder-gray-500
            focus:border-indigo-500 dark:focus:border-indigo-400
            focus:ring-indigo-500 dark:focus:ring-indigo-400
            rounded-md shadow-sm" type="text" name="name" :value="old('name')" required autofocus
            autocomplete="name" />
        <x-input-error :messages="$errors->get('name')" class="mt-2 dark:text-red-400" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Jenis Identitas -->
        <div>
            <x-input-label for="type_id" :value="__('Jenis Identitas')" class="dark:text-gray-200" />
            <select name="type_id" id="type_id" required class="block mt-1 w-full rounded-md
                    border border-gray-300 dark:border-gray-600
                    bg-white dark:bg-gray-800
                    text-gray-900 dark:text-gray-100
                    shadow-sm
                    focus:border-indigo-500 dark:focus:border-indigo-400
                    focus:ring-indigo-500 dark:focus:ring-indigo-400">
                <option value="KTP" {{ old('type_id')=='KTP' ? 'selected' : '' }}>KTP</option>
                <option value="SIM" {{ old('type_id')=='SIM' ? 'selected' : '' }}>SIM</option>
                <option value="Paspor" {{ old('type_id')=='Paspor' ? 'selected' : '' }}>Paspor</option>
                <option value="KITAS" {{ old('type_id')=='KITAS' ? 'selected' : '' }}>KITAS</option>
            </select>
            <x-input-error :messages="$errors->get('type_id')" class="mt-2 dark:text-red-400" />
        </div>

        <!-- Nomor Identitas -->
        <div>
            <x-input-label for="no_id" :value="__('Nomor Identitas')" class="dark:text-gray-200" />
            <x-text-input id="no_id" class="block mt-1 w-full
                border border-gray-300 dark:border-gray-600
                bg-white dark:bg-gray-800
                text-gray-900 dark:text-gray-100
                placeholder-gray-400 dark:placeholder-gray-500
                focus:border-indigo-500 dark:focus:border-indigo-400
                focus:ring-indigo-500 dark:focus:ring-indigo-400
                rounded-md shadow-sm" type="text" name="no_id" :value="old('no_id')" required maxlength="17" />
            <x-input-error :messages="$errors->get('no_id')" class="mt-2 dark:text-red-400" />
        </div>
    </div>

    <!-- Alamat Email -->
    <div>
        <x-input-label for="email" :value="__('Email')" class="dark:text-gray-200" />
        <x-text-input id="email" class="block mt-1 w-full
            border border-gray-300 dark:border-gray-600
            bg-white dark:bg-gray-800
            text-gray-900 dark:text-gray-100
            placeholder-gray-400 dark:placeholder-gray-500
            focus:border-indigo-500 dark:focus:border-indigo-400
            focus:ring-indigo-500 dark:focus:ring-indigo-400
            rounded-md shadow-sm" type="email" name="email" :value="old('email')" required autocomplete="username" />
        <x-input-error :messages="$errors->get('email')" class="mt-2 dark:text-red-400" />
    </div>

    <!-- Nomor Telepon -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Kode Negara -->
        <div>
            <x-input-label for="country_code" :value="__('Kode Negara')" class="dark:text-gray-200" />
            <select id="country_code" name="country_code" required class="block mt-1 w-full rounded-md
                    border border-gray-300 dark:border-gray-600
                    bg-white dark:bg-gray-800
                    text-gray-900 dark:text-gray-100
                    shadow-sm
                    focus:border-indigo-500 dark:focus:border-indigo-400
                    focus:ring-indigo-500 dark:focus:ring-indigo-400">
                <option value="+62" {{ old('country_code')=='+62' ? 'selected' : '' }}>+62 - Indonesia</option>
                <option value="+60" {{ old('country_code')=='+60' ? 'selected' : '' }}>+60 - Malaysia</option>
                <option value="+65" {{ old('country_code')=='+65' ? 'selected' : '' }}>+65 - Singapore</option>
                <option value="+1" {{ old('country_code')=='+1' ? 'selected' : '' }}>+1 - United States</option>
                <option value="+44" {{ old('country_code')=='+44' ? 'selected' : '' }}>+44 - United Kingdom</option>
                <option value="+81" {{ old('country_code')=='+81' ? 'selected' : '' }}>+81 - Japan</option>
                <option value="+86" {{ old('country_code')=='+86' ? 'selected' : '' }}>+86 - China</option>
                <option value="+91" {{ old('country_code')=='+91' ? 'selected' : '' }}>+91 - India</option>
                <option value="+82" {{ old('country_code')=='+82' ? 'selected' : '' }}>+82 - South Korea</option>
                <option value="+49" {{ old('country_code')=='+49' ? 'selected' : '' }}>+49 - Germany</option>
                <option value="+33" {{ old('country_code')=='+33' ? 'selected' : '' }}>+33 - France</option>
                <option value="+61" {{ old('country_code')=='+61' ? 'selected' : '' }}>+61 - Australia</option>
                <option value="+34" {{ old('country_code')=='+34' ? 'selected' : '' }}>+34 - Spain</option>
                <option value="+39" {{ old('country_code')=='+39' ? 'selected' : '' }}>+39 - Italy</option>
                <option value="+7" {{ old('country_code')=='+7' ? 'selected' : '' }}>+7 - Russia</option>
                <option value="+971" {{ old('country_code')=='+971' ? 'selected' : '' }}>+971 - United Arab Emirates
                </option>
            </select>
            <x-input-error :messages="$errors->get('country_code')" class="mt-2 dark:text-red-400" />
        </div>

        <!-- No Handphone -->
        <div>
            <x-input-label for="no_tlp" :value="__('No Handphone')" class="dark:text-gray-200" />
            <x-text-input id="no_tlp" class="block mt-1 w-full
                border border-gray-300 dark:border-gray-600
                bg-white dark:bg-gray-800
                text-gray-900 dark:text-gray-100
                placeholder-gray-400 dark:placeholder-gray-500
                focus:border-indigo-500 dark:focus:border-indigo-400
                focus:ring-indigo-500 dark:focus:ring-indigo-400
                rounded-md shadow-sm" type="text" name="no_tlp" :value="old('no_tlp')" required
                placeholder="81234567890" />
            <x-input-error :messages="$errors->get('no_tlp')" class="mt-2 dark:text-red-400" />
        </div>
    </div>

    <!-- Kata Sandi -->
    <div>
        <x-input-label for="password" :value="__('Kata Sandi')" class="dark:text-gray-200" />
        <x-text-input id="password" class="block mt-1 w-full
            border border-gray-300 dark:border-gray-600
            bg-white dark:bg-gray-800
            text-gray-900 dark:text-gray-100
            placeholder-gray-400 dark:placeholder-gray-500
            focus:border-indigo-500 dark:focus:border-indigo-400
            focus:ring-indigo-500 dark:focus:ring-indigo-400
            rounded-md shadow-sm" type="password" name="password" required autocomplete="new-password" />
        <x-input-error :messages="$errors->get('password')" class="mt-2 dark:text-red-400" />
    </div>

    <!-- Konfirmasi Kata Sandi -->
    <div>
        <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi')" class="dark:text-gray-200" />
        <x-text-input id="password_confirmation" class="block mt-1 w-full
            border border-gray-300 dark:border-gray-600
            bg-white dark:bg-gray-800
            text-gray-900 dark:text-gray-100
            placeholder-gray-400 dark:placeholder-gray-500
            focus:border-indigo-500 dark:focus:border-indigo-400
            focus:ring-indigo-500 dark:focus:ring-indigo-400
            rounded-md shadow-sm" type="password" name="password_confirmation" required autocomplete="new-password" />
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
@endsection