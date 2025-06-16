@extends('layouts.app')

@section('namePage', 'Edit Trainer Eksternal')

@section('content')
    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">Edit Trainer Eksternal</h1>

        <form id="adminForm" action="{{ route('trainer.update', $trainer->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-white">Nama Lengkap</label>
                <input type="text" name="name" id="name" required value="{{ old('name', $trainer->name) }}"
                    class="mt-1 block w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-white">Email</label>
                <input type="email" name="email" id="email" required value="{{ old('email', $trainer->email) }}"
                    class="mt-1 block w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-4">
                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 dark:text-white">Jenis
                    Kelamin</label>
                <select name="jenis_kelamin" id="jenis_kelamin"
                    class="mt-1 block w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Pria" {{ old('jenis_kelamin', $trainer->jenis_kelamin) == 'Pria' ? 'selected' : '' }}>
                        Laki-laki</option>
                    <option value="Wanita"
                        {{ old('jenis_kelamin', $trainer->jenis_kelamin) == 'Wanita' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 dark:text-white">Tempat
                    Lahir</label>
                <input type="text" name="tempat_lahir" id="tempat_lahir"
                    value="{{ old('tempat_lahir', $trainer->tempat_lahir) }}"
                    class="mt-1 block w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-4">
                <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 dark:text-white">Tanggal
                    Lahir</label>
                <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                    value="{{ old('tanggal_lahir', $trainer->tanggal_lahir) }}"
                    class="mt-1 block w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-4">
                <label for="warga_negara" class="block text-sm font-medium text-gray-700 dark:text-white">Warga
                    Negara</label>
                <input type="text" name="warga_negara" id="warga_negara"
                    value="{{ old('warga_negara', $trainer->warga_negara) }}"
                    class="mt-1 block w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-4">
                <label for="alamat" class="block text-sm font-medium text-gray-700 dark:text-white">Alamat</label>
                <textarea name="alamat" id="alamat" rows="3"
                    class="mt-1 block w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('alamat', $trainer->alamat) }}</textarea>
            </div>

            <div class="mb-4">
                <label for="no_id" class="block text-sm font-medium text-gray-700 dark:text-white">No. Identitas</label>
                <input type="text" name="no_id" id="no_id" value="{{ old('no_id', $trainer->no_id) }}"
                    class="mt-1 block w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-4">
                <label for="no_tlp" class="block text-sm font-medium text-gray-700 dark:text-white">No. Telepon</label>
                <input type="text" name="no_tlp" id="no_tlp" value="{{ old('no_tlp', $trainer->no_tlp) }}"
                    class="mt-1 block w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-6">
                <label for="pekerjaan" class="block text-sm font-medium text-gray-700 dark:text-white">Pekerjaan</label>
                <select name="pekerjaan" id="pekerjaan"
                    class="mt-1 block w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">-- Pilih Pekerjaan --</option>
                    @foreach (['Pelajar/Mahasiswa', 'PNS', 'TNI/Polri', 'Pegawai Negeri', 'Karyawan Swasta', 'Wiraswasta', 'Petani', 'Peternak', 'Nelayan', 'Buruh', 'Pensiunan', 'Ibu Rumah Tangga', 'Dokter', 'Perawat', 'Guru/Dosen', 'Sopir', 'Pengacara', 'Arsitek', 'Seniman/Artis', 'Programmer', 'Lainnya'] as $job)
                        <option value="{{ $job }}"
                            {{ old('pekerjaan', $trainer->pekerjaan) == $job ? 'selected' : '' }}>{{ $job }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-white">Password Baru
                    (Opsional)</label>
                <input type="password" name="password" id="password"
                    class="mt-1 block w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    placeholder="Biarkan kosong jika tidak ingin diubah">
            </div>

            <div class="mb-4">
                <label for="password_confirmation"
                    class="block text-sm font-medium text-gray-700 dark:text-white">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="mt-1 block w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    placeholder="Ulangi password baru">
            </div>

            <div class="flex justify-end gap-4">
                <button type="button" onclick="document.getElementById('modalBack').classList.remove('hidden')"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-sm rounded text-gray-800 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500">
                    Batal
                </button>

                <button type="button" onclick="document.getElementById('modalSubmit').classList.remove('hidden')"
                    class="px-4 py-2 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    @include('components.modal-batal', [
        'route' => route('trainer.index'),
    ])
    @include('components.modal-submit', [
        'form' => 'adminForm',
        'message' => 'Apakah Anda yakin ingin menyimpan perubahan data trainer ini?',
    ])
@endsection
