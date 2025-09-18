@extends('layouts.app')

@section('namePage', 'Riwayat Post Test')

@section('content')
    <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md">
        <a href="{{ route('dashboard') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-600 transition cursor-pointer sm:w-auto">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <br><br>
        <h2 class="text-3xl font-bold mb-6 text-center text-gray-800 dark:text-white">📚 Riwayat Post Test Anda</h2>

        @if ($results->isEmpty())
            <div class="text-center text-gray-500 dark:text-gray-300">
                Belum ada post test yang pernah dikerjakan.
            </div>
        @else
            <div class="overflow-x-auto">
                <table
                    class="min-w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-md overflow-hidden">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left">📘 Ebook</th>
                            <th class="px-4 py-3 text-left">📝 Sesi</th>
                            <th class="px-4 py-3 text-left">⏱️ Durasi</th>
                            <th class="px-4 py-3 text-left">📊 Skor</th>
                            <th class="px-4 py-3 text-left">🗓️ Tanggal</th>
                            <th class="px-4 py-3 text-center">🔍 Detail</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 dark:text-gray-100 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($results as $result)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3">{{ $result->ebook->title ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $result->session->title ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $result->session->duration ?? '-' }} menit</td>
                                <td class="px-4 py-3 font-semibold text-green-600 dark:text-green-400">
                                    {{ $result->score }}/100
                                </td>
                                <td class="px-4 py-3">{{ $result->created_at->format('d M Y, H:i') }}</td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('riwayat.show', $result->id) }}"
                                        class="text-blue-600 hover:underline">Lihat</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
