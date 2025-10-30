@extends('layouts.app')

@section('namePage', 'Edit Jadwal Absensi')

@section('content')
    <div class="max-w-2xl mx-auto p-5 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
        <div class="flex items-center justify-between mb-5">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Jadwal Absensi</h1>
            <a href="{{ route('absensi.index') }}" class="text-blue-500 hover:text-blue-700">
                <i class="fa-solid fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        @if (session('Alert'))
            <div class="text-green-700 text-sm mb-4">
                {{ session('Alert') }}
            </div>
        @endif

        <form action="{{ route('absensi.update', $jadwal->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Judul Sesi --}}
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul Sesi</label>
                <input type="text" name="title" id="title" value="{{ old('title', $jadwal->title) }}"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded px-3 py-2 mt-1 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                    required>
                @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tanggal --}}
            <div class="mb-4">
                <label for="tanggal" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', $jadwal->tanggal) }}"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded px-3 py-2 mt-1 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                    required>
                @error('tanggal')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Post Test Session --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Sesi Post-Test</label>

                {{-- Search Input --}}
                <input type="text" id="searchSession" placeholder="Cari sesi post-test..."
                    class="w-full border border-gray-300 dark:border-gray-600 rounded px-3 py-2 mt-1 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">

                {{-- Radio Buttons --}}
                <div id="sessionList"
                    class="mt-3 max-h-60 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded p-3 bg-white dark:bg-gray-700 grid grid-cols-1 gap-2">
                    @foreach ($postTestSessions as $session)
                        <div class="session-item relative border border-gray-200 dark:border-gray-600 rounded p-3 bg-gray-50 dark:bg-gray-600 hover:bg-gray-100 dark:hover:bg-gray-500 cursor-pointer transition-all duration-200"
                            onclick="selectSession({{ $session->id }})">
                            <input type="radio" name="post_test_session_id" id="session-{{ $session->id }}"
                                value="{{ $session->id }}"
                                {{ old('post_test_session_id', $jadwal->post_test_session_id) == $session->id ? 'checked' : '' }}
                                required class="hidden">
                            <label for="session-{{ $session->id }}"
                                class="text-sm text-gray-900 dark:text-white cursor-pointer w-full flex items-center">
                                <div class="custom-radio mr-3 flex-shrink-0">
                                    <div
                                        class="radio-circle w-4 h-4 border-2 border-gray-400 dark:border-gray-500 rounded-full flex items-center justify-center">
                                        <div
                                            class="radio-dot w-2 h-2 bg-blue-600 rounded-full opacity-0 transition-opacity">
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium">{{ $session->title }}</div>
                                    <div class="text-xs text-gray-600 dark:text-gray-300">{{ $session->duration }} Menit
                                    </div>
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>

                @error('post_test_session_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol Simpan --}}
            <div class="flex justify-end gap-2">
                <a href="{{ route('absensi.index') }}"
                    class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white rounded hover:bg-gray-400 dark:hover:bg-gray-500">Batal</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('searchSession').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const sessionItems = document.querySelectorAll('.session-item');

            sessionItems.forEach(item => {
                const label = item.querySelector('label').textContent.toLowerCase();
                if (label.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        function selectSession(sessionId) {
            // Uncheck all radio buttons
            document.querySelectorAll('input[name="post_test_session_id"]').forEach(radio => {
                radio.checked = false;
            });

            // Check the selected radio button
            const selectedRadio = document.getElementById('session-' + sessionId);
            selectedRadio.checked = true;

            // Update visual state
            document.querySelectorAll('.session-item').forEach(item => {
                item.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-50', 'dark:bg-blue-900');
                const radioDot = item.querySelector('.radio-dot');
                radioDot.classList.add('opacity-0');
            });

            // Highlight selected item
            const selectedItem = selectedRadio.closest('.session-item');
            selectedItem.classList.add('ring-2', 'ring-blue-500', 'bg-blue-50', 'dark:bg-blue-900');
            const selectedRadioDot = selectedItem.querySelector('.radio-dot');
            selectedRadioDot.classList.remove('opacity-0');
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            const checkedRadio = document.querySelector('input[name="post_test_session_id"]:checked');
            if (checkedRadio) {
                selectSession(checkedRadio.value);
            }
        });
    </script>
@endsection
