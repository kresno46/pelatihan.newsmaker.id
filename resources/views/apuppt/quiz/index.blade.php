@extends('layouts.app')

@section('namePage', 'Kuis')

@section('content')
    <header
        class="w-full bg-white dark:bg-gray-800 shadow rounded-lg mb-5 p-4 sm:p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    {{ __('Kuis / Post Test') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Kelola daftar post test: tambah, ubah, lihat laporan, atau hapus.') }}
                </p>
            </div>

            <div class="flex items-center">
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                        class="text-xs bg-green-100 dark:bg-green-200 text-green-800 py-1 px-3 rounded-lg mr-3"
                        aria-live="polite">
                        {{ session('success') }}
                    </div>
                @elseif (session('alert'))
                    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                        class="text-xs bg-green-100 dark:bg-green-200 text-green-800 py-1 px-3 rounded-lg mr-3"
                        aria-live="polite">
                        {{ session('alert') }}
                    </div>
                @endif

                <a href="{{ route('apuppt.posttest.create') }}"
                    class="bg-blue-500 px-4 py-2 text-sm hover:bg-blue-600 text-white rounded transition-all text-center">
                    {{ __('Tambah') }}
                </a>
            </div>
        </div>
    </header>

    <div class="mb-4 bg-white dark:bg-gray-800 p-2 rounded-lg border border-gray-100 dark:border-gray-700">
        <div class="grid grid-cols-2 gap-2">
            <a href="{{ route('apuppt.posttest.index', ['jenis' => 'posttest']) }}"
                class="text-center py-2 px-3 text-sm font-semibold rounded {{ $jenis === 'posttest' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200' }}">
                Soal Post Test
            </a>
            <a href="{{ route('apuppt.posttest.index', ['jenis' => 'ebook']) }}"
                class="text-center py-2 px-3 text-sm font-semibold rounded {{ $jenis === 'ebook' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200' }}">
                Soal Ebook
            </a>
        </div>
    </div>

    <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-100 dark:border-gray-700">
        @if ($sessions->isEmpty())
            <div class="text-center py-12 text-gray-600 dark:text-gray-300">
                <p class="font-medium">{{ __('Belum ada post test.') }}</p>
                <p class="text-sm mt-1">{{ __('Klik "Tambah" untuk membuat post test baru.') }}</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                @foreach ($sessions as $item)
                    <div
                        class="bg-gray-50 dark:bg-gray-900/40 p-5 rounded-lg flex flex-col justify-between gap-4 border border-gray-100 dark:border-gray-700">
                        <div class="w-full flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $item->title }}
                                </h3>
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    <strong>{{ __('Durasi:') }}</strong> {{ $item->duration }} {{ __('menit') }}
                                </p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="w-full grid grid-cols-3 gap-2">
                                {{-- Laporan / Show --}}
                                <a href="{{ route('apuppt.posttest.report', $item->slug) }}"
                                    title="Laporan"
                                    class="bg-green-500 px-3 py-2 text-xs sm:text-sm hover:bg-green-600 text-white rounded transition-all text-center">
                                    <i class="fa-solid fa-chart-line"></i>
                                </a>

                                {{-- Edit --}}
                                <a href="{{ route('apuppt.posttest.edit', $item->slug) }}"
                                    title="Edit"
                                    class="bg-yellow-500 px-3 py-2 text-xs sm:text-sm hover:bg-yellow-600 text-white rounded transition-all text-center">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                {{-- Hapus (DELETE) --}}
                                <button type="button"
                                    onclick="showDeleteModal('{{ route('apuppt.posttest.destroy', $item->slug) }}')"
                                    title="Hapus"
                                    class="w-full bg-red-500 px-3 py-2 text-xs sm:text-sm hover:bg-red-600 text-white rounded transition-all text-center">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>

                            <div class="flex items-center justify-between">
                                {{-- Toggle Status Aktif / Tidak Aktif --}}
                                <form action="{{ route('apuppt.posttest.toggle', $item->slug) }}" method="POST">
                                    @csrf
                                    <label class="relative inline-block w-12 h-6 cursor-pointer">
                                        <input type="checkbox" name="status" onchange="this.form.submit()"
                                            class="sr-only peer" {{ $item->status ? 'checked' : '' }}>
                                        <!-- If status is true (Aktif), check the box -->
                                        <div
                                            class="w-full h-full bg-gray-300 dark:bg-gray-600 rounded-full peer-checked:bg-green-500 transition-colors duration-300">
                                        </div>
                                        <div
                                            class="absolute top-0.5 left-0.5 w-5 h-5 bg-white dark:bg-gray-100 rounded-full transition-transform duration-300 transform peer-checked:translate-x-6">
                                        </div>
                                    </label>
                                </form>

                                <div>
                                    <p
                                        class="text-sm font-bold px-5 py-1 rounded-full 
                                    {{ $item->ebook_id ? 'bg-indigo-600' : ($item->tipe == 'PATL' ? 'bg-red-500' : ($item->tipe == 'PATD' ? 'bg-green-500' : 'bg-blue-500')) }}">
                                        <span class="text-white">{{ $item->ebook_id ? 'SOAL EBOOK' : $item->tipe }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Optional Pagination --}}
            @if (method_exists($sessions, 'links'))
                <div class="mt-4">
                    {{ $sessions->links() }}
                </div>
            @endif
        @endif
    </div>

    <div id="deleteModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center px-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md">
            <div class="px-6 py-5">
                <h3 class="text-xl font-bold text-red-500">
                    <i class="fa-solid fa-triangle-exclamation mr-2"></i>Konfirmasi Hapus
                </h3>
            </div>
            <div class="px-6 py-4 border-t border-b dark:border-gray-700">
                <p class="text-gray-700 dark:text-gray-300">
                    Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            <div class="flex justify-end gap-3 px-6 py-4">
                <button onclick="closeDeleteModal()"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-md dark:bg-gray-600 dark:text-white">
                    Batal
                </button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

@section('scripts')
    <script>
        function showDeleteModal(action) {
            document.getElementById('deleteForm').action = action;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        function toggleStatus(id) {
            // Send an AJAX request to toggle the status (Aktif / Tidak Aktif)
            $.ajax({
                url: '/apuppt/post-test/toggle-status/' + id, // Set this route in your routes file
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    // Update the UI based on the response
                    alert(response.message); // You can show a success message or update the UI dynamically
                },
                error: function(error) {
                    console.log(error);
                    alert('Terjadi kesalahan, silakan coba lagi.');
                }
            });
        }
    </script>
@endsection
@endsection
