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
                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-white">Perusahaan</label>
                <select name="role" id="role" required
                    class="mt-1 block w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">-- Pilih Perusahaan --</option>
                    <option value="Trainer (SGB)" {{ old('role', $trainer->role) == 'Trainer (SGB)' ? 'selected' : '' }}>
                        Trainer (SGB)</option>
                    <option value="Trainer (RFB)" {{ old('role', $trainer->role) == 'Trainer (RFB)' ? 'selected' : '' }}>
                        Trainer (RFB)</option>
                    <option value="Trainer (EWF)" {{ old('role', $trainer->role) == 'Trainer (EWF)' ? 'selected' : '' }}>
                        Trainer (EWF)</option>
                    <option value="Trainer (BPF)" {{ old('role', $trainer->role) == 'Trainer (BPF)' ? 'selected' : '' }}>
                        Trainer (BPF)</option>
                    <option value="Trainer (KPF)" {{ old('role', $trainer->role) == 'Trainer (KPF)' ? 'selected' : '' }}>
                        Trainer (KPF)</option>
                </select>
            </div>

            <div class="mb-4" id="cabangContainer" style="display: none;">
                <label for="cabang" class="block text-sm font-medium text-gray-700 dark:text-white">Cabang</label>
                <select name="cabang" id="cabang"
                    class="mt-1 block w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <!-- Opsi cabang akan ditambahkan dengan JavaScript -->
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

@section('scripts')
    <script>
        // Script untuk menampilkan cabang berdasarkan role
        document.getElementById('role').addEventListener('change', function() {
            const role = this.value;
            const cabangContainer = document.getElementById('cabangContainer');
            const cabangSelect = document.getElementById('cabang');

            // Reset nilai cabang select
            cabangSelect.innerHTML = '<option value="">-- Pilih Cabang --</option>';

            if (role === 'Trainer (SGB)') {
                cabangContainer.style.display = 'block';
                cabangSelect.innerHTML += `
                    <option value="Semarang">Semarang</option>
                    <option value="Makassar">Makassar</option>
                `;
            } else if (role === 'Trainer (RFB)') {
                cabangContainer.style.display = 'block';
                cabangSelect.innerHTML += `
                    <option value="Medan">Medan</option>
                    <option value="Palembang">Palembang</option>
                    <option value="Semarang">Semarang</option>
                    <option value="Jakarta">Jakarta</option>
                    <option value="Surabaya">Surabaya</option>
                    <option value="Pekanbaru">Pekanbaru</option>
                    <option value="Bandung">Bandung</option>
                    <option value="Solo">Solo</option>
                    <option value="Yogyakarta">Yogyakarta</option>
                    <option value="Balikpapan">Balikpapan</option>
                    <option value="Surabaya II">Surabaya II</option>
                `;
            } else if (role === 'Trainer (EWF)') {
                cabangContainer.style.display = 'block';
                cabangSelect.innerHTML += `
                    <option value="Surabaya Trillium">Surabaya Trillium</option>
                    <option value="Manado">Manado</option>
                    <option value="Jakarta">Jakarta</option>
                    <option value="Semarang">Semarang</option>
                    <option value="Surabaya Praxis">Surabaya Praxis</option>
                    <option value="Cirebon">Cirebon</option>
                `;
            } else if (role === 'Trainer (BPF)') {
                cabangContainer.style.display = 'block';
                cabangSelect.innerHTML += `
                    <option value="Jambi">Jambi</option>
                    <option value="Jakarta - Pacific Place Mall">Jakarta - Pacific Place Mall</option>
                    <option value="Pontianak">Pontianak</option>
                    <option value="Malang">Malang</option>
                    <option value="Surabaya">Surabaya</option>
                    <option value="Medan">Medan</option>
                    <option value="Bandung">Bandung</option>
                    <option value="Pekanbaru">Pekanbaru</option>
                    <option value="Banjarmasin">Banjarmasin</option>
                    <option value="Bandar Lampung">Bandar Lampung</option>
                    <option value="Semarang">Semarang</option>
                `;
            } else if (role === 'Trainer (KPF)') {
                cabangContainer.style.display = 'block';
                cabangSelect.innerHTML += `
                    <option value="Yogyakarta">Yogyakarta</option>
                    <option value="Bali">Bali</option>
                    <option value="Makassar">Makassar</option>
                    <option value="Bandung">Bandung</option>
                    <option value="Semarang">Semarang</option>
                `;
            } else {
                cabangContainer.style.display = 'none';
            }
        });

        // Initial check to show the cabang options based on the current role
        document.getElementById('role').dispatchEvent(new Event('change'));
    </script>
@endsection
