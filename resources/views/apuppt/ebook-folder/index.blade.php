@extends('layouts.app')

@section('namePage', 'APUPPT Folder Ebook')

@section('content')
<div class="bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-lg">
    <div class="w-full flex items-center gap-2 mb-5">
        <a href="{{ route('dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-600 transition"><i class="fa-solid fa-arrow-left text-sm"></i></a>
        <form action="{{ route('apuppt.ebookfolder.index') }}" method="GET" class="flex items-center gap-2 flex-grow">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Folder APUPPT..." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800" />
        </form>
        <a href="{{ route('apuppt.ebookfolder.index') }}" class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-600 transition">Reset</a>
        <a href="{{ route('apuppt.ebookfolder.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-600 transition">Tambah Folder</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @forelse ($folders as $folder)
            <div class="shadow-lg rounded-2xl bg-zinc-50 dark:bg-zinc-700 p-6 space-y-4 border">
                <h1 class="text-xl font-semibold">{{ $folder->folder_name }}</h1>
                <p class="text-sm">
                    Status:
                    <span class="font-semibold {{ $folder->is_active ? 'text-green-600' : 'text-red-600' }}">
                        {{ $folder->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </p>
                <p class="text-sm">{{ $folder->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                <p class="text-sm">{{ $folder->ebooks_count }} Ebook APUPPT</p>
                <div class="flex items-stretch gap-2">
                    <a href="{{ route('apuppt.ebook.index', $folder->slug) }}" class="w-full px-4 py-1 bg-blue-500 text-white rounded-lg text-sm text-center">Lihat</a>
                    <a href="{{ route('apuppt.ebookfolder.edit', $folder->slug) }}" class="w-full px-4 py-1 bg-yellow-500 text-white rounded-lg text-sm text-center">Edit</a>
                    <form action="{{ route('apuppt.ebookfolder.toggle', $folder->id) }}" method="POST" class="w-full">@csrf
                        <button type="submit" class="w-full px-4 py-1 {{ $folder->is_active ? 'bg-orange-500' : 'bg-green-500' }} text-white rounded-lg text-sm text-center">
                            {{ $folder->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                    <button type="button" class="w-full px-4 py-1 bg-red-500 text-white rounded-lg text-sm text-center"
                        onclick="showDeleteModal('{{ route('apuppt.ebookfolder.destroy', $folder->id) }}')">Hapus</button>
                </div>
            </div>
        @empty
            <div class="col-span-4 text-center py-10 text-gray-500">Tidak ada folder APUPPT ditemukan.</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $folders->links() }}</div>
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
@endsection

@section('scripts')
<script>
    function showDeleteModal(action) {
        document.getElementById('deleteForm').action = action;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endsection
