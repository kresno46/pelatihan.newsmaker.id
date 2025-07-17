@extends('layouts.app')

@section('namePage', 'Tambah Folder Outlook')

@section('content')
    <div class="card p-6 bg-white dark:bg-gray-800 rounded shadow">

    <div class="space-y-5">
        <div class="text-lg font-semibold">
            Create New Outlook Folder
        </div>

        <hr>

        <form method="POST" action="{{ route('outlookfolder.store') }}">
            @csrf

                {{-- Folder Name --}}
                <div class="mb-4">
                    <label for="folder_name" class="block text-sm font-medium text-gray-700 mb-1">
                        Folder Name
                    </label>
                    <input type="text" id="folder_name" name="folder_name"
                           class="w-full px-4 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-200 focus:outline-none
                                  @error('folder_name') border-red-500 @enderror"
                           value="{{ old('folder_name') }}" required>
                    @error('folder_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="mb-4">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">
                        Description
                    </label>
                    <textarea id="deskripsi" name="deskripsi" rows="3"
                              class="w-full px-4 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-200 focus:outline-none
                                     @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="flex items-center space-x-3">
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        Create Folder
                    </button>
                    <a href="{{ route('outlookfolder.index') }}"
                       class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 transition">
                        Cancel
                    </a>
                </div>
            </form>
    </div>
@endsection
