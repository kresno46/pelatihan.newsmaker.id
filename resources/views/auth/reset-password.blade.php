@extends('layouts.guest')

@section('content')
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                {{ __('Email') }}
            </label>
            <input id="email" type="email" name="email"
                value="{{ is_array(old('email', $email)) ? old('email', $email)[0] : old('email', $email) }}" required
                autofocus autocomplete="username"
                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-600 dark:focus:ring-indigo-600" />
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                {{ __('Password') }}
            </label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-600 dark:focus:ring-indigo-600" />
            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                {{ __('Confirm Password') }}
            </label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                autocomplete="new-password"
                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-600 dark:focus:ring-indigo-600" />
            @error('password_confirmation')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-end">
            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Reset Password') }}
            </button>
        </div>
    </form>
@endsection
