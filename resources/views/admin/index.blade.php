@extends('layouts.app')

@section('namePage', 'Admin')

@section('content')
    <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-lg">
        <div class="flex items-center justify-between  mb-5">
            <h1 class="text-2xl font-bold">Daftar Admin</h1>

            <div class="flex items-center gap-4">
                @if (session('Alert'))
                    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                        class="text-xs bg-green-200 text-green-800 py-1 px-3 rounded-lg">
                        {{ session('Alert') }}
                    </div>
                @endif

                <a href="{{ route('admin.create') }}"
                    class="inline-block px-4 py-2 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">
                    Tambah Admin
                </a>
            </div>
        </div>

        <hr>

        <div class="overflow-x-auto bg-white rounded-lg mt-5 shadow-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">#</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Nama</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-700">Email</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-700">Tanggal Dibuat</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($admin as $item)
                        @php
                            $no = $loop->index + 1;
                        @endphp
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $no }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $item->name }}</td>
                            <td class="px-6 py-4 text-sm text-center text-gray-800">{{ $item->email }}</td>
                            <td class="px-6 py-4 text-sm text-center text-gray-800">{{ $item->created_at }}</td>
                            <td class="px-6 py-4 text-sm text-center flex gap-2">
                                <a href="{{ route('admin.edit', $item->id) }}"
                                    class="w-full px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">Edit</a>
                                @php
                                    $isSelf = auth()->user()->id === $item->id;
                                @endphp

                                @if (!$isSelf)
                                    <button type="button"
                                        onclick="openDeleteModal({{ $item->id }}, '{{ $item->name }}')"
                                        class="w-full px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">
                                        Hapus
                                    </button>
                                @endif

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-800 text-center" colspan="5">Belum ada admin</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div id="modalDelete"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm transition-opacity duration-200 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md transform transition-all scale-95">
            <!-- Header -->
            <div class="px-6 py-5">
                <h3 class="text-xl font-bold text-red-500 dark:text-red-500 flex items-center gap-3">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Konfirmasi Hapus</span>
                </h3>
            </div>

            <!-- Body -->
            <div class="px-6 py-4 border-t border-b dark:border-gray-700">
                <p class="text-gray-700 dark:text-gray-300">
                    Apakah Anda yakin ingin menghapus admin <strong id="adminNameToDelete" class="font-semibold"></strong>?
                </p>
            </div>

            <!-- Footer / Actions -->
            <div class="flex justify-end gap-3 px-6 py-4">
                <button onclick="closeDeleteModal()"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-md text-gray-800 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500 transition">
                    Batal
                </button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

@endsection


@section('scripts')
    <script>
        function openDeleteModal(id, name) {
            document.getElementById('modalDelete').classList.remove('hidden');
            document.getElementById('adminNameToDelete').textContent = name;
            document.getElementById('deleteForm').action = '/admin/' + id;
        }

        function closeDeleteModal() {
            document.getElementById('modalDelete').classList.add('hidden');
        }
    </script>
@endsection
