@extends('layouts.app')

@section('namePage', 'Laporan Kuesioner Feedback')

@section('content')
    <header class="w-full bg-white dark:bg-gray-800 shadow rounded-lg mb-5 p-4 sm:p-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    Laporan Kuesioner — {{ $form->apuppt_pt_scope }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Total {{ $totalResponses }} user sudah mengisi kuesioner ini.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('apuppt.feedback.report.export', array_merge(['form' => $form], request()->query())) }}"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-md font-medium transition">
                    <i class="fas fa-file-csv mr-1"></i> Export CSV
                </a>
                <a href="{{ route('apuppt.feedback.edit', ['pt' => $form->apuppt_pt_scope]) }}"
                    class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded-md font-medium transition">
                    Kembali
                </a>
            </div>
        </div>
    </header>

    {{-- Rekap Rating --}}
    @if ($form->questions->where('type', 'rating')->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-4">
            @foreach ($form->questions->where('type', 'rating') as $q)
                <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow">
                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $q->question_text }}</div>
                    <div class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                        {{ $ratingAverages[$q->id] ?? '—' }} <span class="text-sm text-gray-500">/ 5</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Filter --}}
    <div class="mb-4 p-4 sm:p-5 bg-white dark:bg-gray-800 rounded-xl shadow">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div>
                <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Cari Nama</label>
                <input type="text" name="q" value="{{ request('q') }}"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
            </div>
            <div>
                <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Cabang</label>
                <input type="text" name="cabang" value="{{ request('cabang') }}"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white text-sm">Terapkan</button>
                <a href="{{ route('apuppt.feedback.report', $form) }}"
                    class="px-4 py-2 rounded bg-red-500 hover:bg-red-600 text-white text-sm">Reset</a>
            </div>
        </form>
    </div>

    {{-- Tabel Respons --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
        @if ($responses->isEmpty())
            <div class="text-center py-12 text-gray-600 dark:text-gray-300">
                Belum ada respons kuesioner.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/40">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cabang</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal Isi</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($responses as $index => $response)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30">
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    {{ ($responses->firstItem() ?? 1) + $index }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                    {{ optional($response->user)->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    {{ optional($response->user)->cabang ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    {{ optional($response->submitted_at)->format('d F Y - H:i') }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <button type="button" class="text-blue-600 dark:text-blue-400 hover:underline"
                                        onclick="document.getElementById('detail-{{ $response->id }}').classList.toggle('hidden')">
                                        Lihat Jawaban
                                    </button>
                                </td>
                            </tr>
                            <tr id="detail-{{ $response->id }}" class="hidden">
                                <td colspan="5" class="px-4 py-4 bg-gray-50 dark:bg-gray-900/30">
                                    <div class="space-y-3">
                                        @foreach ($form->questions as $q)
                                            @php $answer = $response->answers->firstWhere('question_id', $q->id); @endphp
                                            <div>
                                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $q->question_text }}</p>
                                                @if ($q->type === 'rating')
                                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                        {{ optional($answer)->rating_value ?? '-' }} / 5
                                                    </p>
                                                @else
                                                    <p class="text-sm text-gray-900 dark:text-gray-100">
                                                        {{ optional($answer)->answer_text ?? '-' }}
                                                    </p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $responses->links() }}
            </div>
        @endif
    </div>
@endsection
