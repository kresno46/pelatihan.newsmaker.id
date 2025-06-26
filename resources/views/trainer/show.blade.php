@extends('layouts.app')

@section('namePage', 'Detail ' . $trainer->name)

@section('content')
    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl">
        <h1 class="text-3xl font-bold mb-8 text-gray-800 dark:text-white border-b pb-4">Detail {{ $trainer->name ?? '-' }}
        </h1>

        <div class="grid md:grid-cols-2 gap-6 text-gray-700 dark:text-gray-200">
            <div>
                <div class="mb-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Nama Lengkap</p>
                    <p class="text-lg font-semibold">{{ $trainer->name ?? '-' }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Email</p>
                    <p class="text-lg font-semibold">{{ $trainer->email ?? '-' }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Jenis Kelamin</p>
                    <p class="text-lg font-semibold">{{ $trainer->jenis_kelamin ?? '-' }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tempat Lahir</p>
                    <p class="text-lg font-semibold">{{ $trainer->tempat_lahir ?? '-' }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Lahir</p>
                    <p class="text-lg font-semibold">
                        {{ $trainer->tanggal_lahir ? \Carbon\Carbon::parse($trainer->tanggal_lahir)->format('d M Y') : '-' }}
                    </p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Warga Negara</p>
                    <p class="text-lg font-semibold">{{ $trainer->warga_negara ?? '-' }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Status Verifikasi Email</p>

                    <p>
                        <span>{{ $trainer->email_verified_at ? \Carbon\Carbon::parse($trainer->email_verified_at)->format('d M Y H:i    ') : '' }}</span>
                        {{ is_null($trainer->email_verified_at) ? '' : ' ──── ' }} <span
                            class="mt-1 inline-block py-1 px-4 rounded-full text-xs font-medium
                            {{ is_null($trainer->email_verified_at) ? 'bg-yellow-200 text-yellow-700' : 'bg-green-200 text-green-800' }}">
                            {{ is_null($trainer->email_verified_at) ? 'Belum Terverifikasi' : 'Terverifikasi' }}
                        </span>
                    </p>
                </div>
            </div>

            <div>
                <div class="mb-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Alamat</p>
                    <p class="text-lg font-semibold">{{ $trainer->alamat ?? '-' }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">No. Identitas</p>
                    <p class="text-lg font-semibold">{{ $trainer->no_id ?? '-' }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">No. Telepon</p>
                    <p class="text-lg font-semibold">{{ $trainer->no_tlp ?? '-' }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pekerjaan</p>
                    <p class="text-lg font-semibold">{{ $trainer->pekerjaan ?? '-' }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Dibuat Pada</p>
                    <p class="text-lg font-semibold">
                        {{ $trainer->created_at ? $trainer->created_at->format('d M Y H:i') : '-' }}
                    </p>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Diperbarui Pada</p>
                    <p class="text-lg font-semibold">
                        {{ $trainer->updated_at ? $trainer->updated_at->format('d M Y H:i') : '-' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-4">
            {{-- Kirim Verifikasi --}}
            {{-- <a href="{{ route('trainer.verify', $trainer->id) }}" --}}
            <a href="{{ route('trainer.edit', $trainer->id) }}"
                class="px-5 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg shadow">
                Edit
            </a>
            {{-- Kembali --}}
            <a href="{{ route('trainer.index') }}"
                class="px-5 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold rounded-lg shadow dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500">
                Kembali
            </a>
        </div>
    </div>
@endsection
