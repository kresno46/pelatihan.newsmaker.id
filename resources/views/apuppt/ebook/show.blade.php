@extends('layouts.app')
@section('namePage', $ebook->title)
@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
    <div class="space-y-5">
        <div class="flex items-center justify-between">
            <a href="{{ route('apuppt.ebook.index', $folder->slug) }}" class="bg-gray-200 px-4 py-2 rounded-lg text-sm">Kembali</a>
            <div class="flex gap-2">
                <a href="{{ route('apuppt.ebook.quiz', [$folder->slug, $ebook->slug]) }}" title="Kelola Soal" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm"><i class="fa-solid fa-clipboard-question"></i></a>
                <a href="{{ route('apuppt.ebook.edit', [$folder->slug, $ebook->slug]) }}" title="Edit" class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm"><i class="fa-solid fa-pen-to-square"></i></a>
                <button type="button" title="Hapus" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm"
                    onclick="showDeleteModal('{{ route('apuppt.ebook.destroy', [$folder->slug, $ebook->slug]) }}')"><i class="fa-solid fa-trash-can"></i></button>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-5">
            <img src="{{ asset($ebook->cover) }}" alt="{{ $ebook->title }}" class="border border-gray-400 rounded-lg overflow-hidden w-full md:w-80">
            <div class="w-full space-y-5">
                <div><label class="text-gray-700">Judul:</label><div class="bg-gray-200 w-full rounded-lg p-3">{{ $ebook->title }}</div></div>
                <div><label class="text-gray-700">Deskripsi:</label><div class="bg-gray-200 w-full rounded-lg p-3">{{ $ebook->deskripsi }}</div></div>
                <a href="{{ asset($ebook->file) }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 py-3 w-full text-center text-white rounded-lg block"><i class="fa-solid fa-download me-2"></i> Download File</a>
                @if ($ebook->postTestSession)
                    <a href="{{ route('apuppt.posttest.edit', $ebook->postTestSession) }}" class="bg-indigo-600 hover:bg-indigo-700 py-3 w-full text-center text-white rounded-lg block">
                        <i class="fa-solid fa-clipboard-question me-2"></i> Lihat Soal Ebook
                    </a>
                @endif
            </div>
        </div>
    </div>
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
