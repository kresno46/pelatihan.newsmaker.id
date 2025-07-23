@extends('layouts.app')

@section('namePage', 'Outlook')

@section('content')
    <div class="bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-lg">

        {{-- Search & Actions --}}
        <div class="w-full flex items-center gap-2 mb-5">
            <form action="{{ route('outlookfolder.index') }}" method="GET" class="flex items-center gap-2 flex-grow">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama Folder..."
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800" />
            </form>

            <a href="{{ route('outlookfolder.index') }}"
                class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-600 transition">
                Reset
            </a>

            @if (Auth::user()->role === 'Admin')
                <button onclick="document.getElementById('modalUrutkan').classList.remove('hidden')"
                    class="bg-zinc-200 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-zinc-300 transition"
                    title="Ubah Tata Letak">
                    <i class="fa-solid fa-grip"></i>
                </button>

                <a href="{{ route('outlookfolder.create') }}"
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-600 transition"
                    title="Tambah">
                    <span class="block sm:hidden">+</span>
                    <span class="hidden sm:block">Tambah Folder</span>
                </a>
            @endif
        </div>

        {{-- Grid Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @forelse ($FolderOutlooks as $folder)
                <div
                    class="shadow-lg rounded-2xl bg-zinc-50 dark:bg-zinc-700 dark:text-gray-100 p-6 space-y-4 transition-all border dark:border-zinc-800 duration-300 hover:shadow-xl h-full">

                    <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                        <div class="w-24 h-24 bg-green-300 dark:bg-green-800 rounded-full overflow-hidden flex-shrink-0">
                            <img src="{{ asset($folder->cover_folder) }}" alt="{{ $folder->folder_name }}"
                                class="w-full h-full object-cover object-center">
                        </div>

                        <div class="flex-1">
                            <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-2">
                                {{ $folder->folder_name }}
                            </h1>

                            <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed line-clamp-1 mb-1">
                                {{ $folder->deskripsi ?? 'Tidak ada deskripsi.' }}
                            </p>

                            <p class="text-gray-600 dark:text-gray-300 text-sm">
                                <i class="fa-solid fa-envelope-open mr-1"></i>
                                {{ $folder->outlooks_count }} Outlook dalam materi ini
                            </p>
                        </div>
                    </div>

                    <div class="flex items-stretch gap-2 mt-4">
                        {{-- Tombol Lihat Outlook --}}
                        <a href="{{ route('outlook.index', $folder->slug) }}"
                            class="w-full px-4 py-1 bg-green-500 text-white rounded-lg text-sm hover:bg-green-600 transition text-center block">
                            Lihat
                        </a>

                        @if (Auth::user()->role === 'Admin')
                            {{-- Tombol Edit Folder --}}
                            <a href="{{ route('outlookfolder.edit', $folder->slug) }}"
                                class="w-full px-4 py-1 bg-yellow-500 text-white rounded-lg text-sm hover:bg-yellow-600 transition text-center block">
                                Edit
                            </a>

                            {{-- Tombol Hapus Folder --}}
                            <button type="button"
                                onclick="document.getElementById('modalDelete-{{ $folder->id }}').classList.remove('hidden')"
                                class="w-full px-4 py-1 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600 transition text-center">
                                Hapus
                            </button>

                            {{-- Modal khusus untuk ID ini --}}
                            <div id="modalDelete-{{ $folder->id }}"
                                class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center px-4">
                                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md">
                                    <div class="px-6 py-5">
                                        <h3 class="text-xl font-bold text-red-500">
                                            <i class="fa-solid fa-triangle-exclamation mr-2"></i>Konfirmasi Hapus
                                        </h3>
                                    </div>

                                    <div class="px-6 py-4 border-t border-b dark:border-gray-700">
                                        <p class="text-gray-700 dark:text-gray-300">
                                            Apakah Anda yakin ingin menghapus Outlook
                                            "<strong>{{ $folder->folder_name }}</strong>"?
                                            Tindakan ini tidak dapat dibatalkan.
                                        </p>
                                    </div>

                                    <div class="flex justify-end gap-3 px-6 py-4">
                                        <button
                                            onclick="document.getElementById('modalDelete-{{ $folder->id }}').classList.add('hidden')"
                                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-md dark:bg-gray-600 dark:text-white">
                                            Batal
                                        </button>
                                        <form action="{{ route('outlookfolder.destroy', $folder->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-4 text-center py-10">
                    <p class="text-gray-500">Tidak ada folder Outlook ditemukan.</p>
                </div>
            @endforelse
        </div>

        {{-- Modal Urutkan Folder (Admin Only) --}}
        @if (Auth::user()->role === 'Admin')
            <div id="modalUrutkan"
                class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center px-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-xl">
                    <div class="px-6 py-5 border-b border-gray-300 dark:border-gray-700 flex justify-between items-center">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                            <i class="fa-solid fa-grip-vertical mr-2"></i>Urutkan Folder
                        </h3>
                        <button onclick="document.getElementById('modalUrutkan').classList.add('hidden')"
                            class="text-gray-500 hover:text-gray-700 dark:hover:text-white">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>

                    <div class="px-6 py-4">
                        <ul id="sortable-folder" class="space-y-2">
                            @foreach ($FolderOutlooks->sortBy('position') as $folder)
                                <li class="bg-gray-100 dark:bg-gray-700 p-3 rounded-lg cursor-move flex items-center justify-between"
                                    data-id="{{ $folder->id }}">
                                    <span class="text-gray-800 dark:text-white">{{ $folder->folder_name }}</span>
                                    <i class="fa-solid fa-grip-lines text-gray-400"></i>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="px-6 py-4 border-t border-gray-300 dark:border-gray-700 flex justify-end gap-3">
                        <button onclick="document.getElementById('modalUrutkan').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-md dark:bg-gray-600 dark:text-white">
                            Batal
                        </button>
                        <button onclick="simpanUrutanFolder()"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                            Simpan Urutan
                        </button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Pagination --}}
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mt-6 gap-2">
            @if ($FolderOutlooks->total() > 8)
                <div class="w-full">
                    {{ $FolderOutlooks->appends(['search' => request('search')])->links() }}
                </div>
            @else
                <div class="text-sm text-gray-600 dark:text-gray-300 p-2">
                    Menampilkan
                    @if ($FolderOutlooks->total() > 0)
                        {{ $FolderOutlooks->firstItem() }} sampai {{ $FolderOutlooks->lastItem() }} dari total
                        {{ $FolderOutlooks->total() }}
                        hasil
                    @else
                        0 sampai 0 dari total 0 hasil
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script>
        $(function() {
            $("#sortable-folder").sortable({
                placeholder: "bg-blue-100 dark:bg-blue-900"
            });
        });

        function simpanUrutanFolder() {
            var positions = [];
            $('#sortable-folder li').each(function() {
                positions.push($(this).data('id'));
            });

            $.ajax({
                url: "{{ route('outlookfolder.reorder') }}",
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    positions: positions
                },
                success: function(response) {
                    alert(response.message);
                    location.reload(); // Refresh halaman agar urutan terlihat
                },
                error: function() {
                    alert("Terjadi kesalahan saat menyimpan urutan.");
                }
            });
        }
    </script>
@endsection
