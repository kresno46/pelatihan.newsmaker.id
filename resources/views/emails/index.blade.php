@extends('layouts.app')

@section('namePage', 'Inbox')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Inbox</h1>

    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">#</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Subject</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Dari</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Tanggal</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Isi Singkat</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($messages as $index => $message)
                    @php
                        $date = $message->getDate()->get();
                        $formattedDate = is_array($date) ? $date[0]->format('d M Y H:i') : $date->format('d M Y H:i');
                        $from = isset($message->getFrom()[0]) ? $message->getFrom()[0]->mail : '-';
                        $subject = $message->getSubject() ?? '(Tanpa Subjek)';
                        $body = \Illuminate\Support\Str::limit(strip_tags($message->getTextBody()), 100);
                    @endphp
                    <tr onclick="openDetailModal({{ $index }})"
                        class="cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                        data-index="{{ $index }}">
                        <td class="px-4 py-3 text-sm text-gray-800 dark:text-gray-100">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 text-sm text-blue-600 dark:text-blue-300 font-medium">{{ $subject }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $from }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $formattedDate }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $body }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-center text-gray-500 dark:text-gray-300">
                            Tidak ada email ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Detail -->
    <div id="detailModal"
        class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center transition-opacity duration-200">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl p-6 relative">
            <div class="flex justify-between items-start">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Detail Email</h2>
                <button onclick="closeDetailModal()" class="text-gray-600 dark:text-gray-300 text-lg">&times;</button>
            </div>
            <div class="mt-4 space-y-2">
                <div>
                    <p><strong>Subject:</strong> <span id="detailSubject" class="text-gray-700 dark:text-gray-300"></span>
                    </p>
                </div>
                <p><strong>From:</strong> <span id="detailFrom" class="text-gray-700 dark:text-gray-300"></span></p>
                <p><strong>Date:</strong> <span id="detailDate" class="text-gray-700 dark:text-gray-300"></span></p>
                <div class="mt-4">
                    <strong>Isi:</strong>
                    <p id="detailBody" class="text-gray-700 dark:text-gray-300 whitespace-pre-line mt-2"></p>
                </div>
            </div>
            <div class="mt-6 text-right">
                <button onclick="closeDetailModal()"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded dark:bg-gray-600 dark:text-white">
                    Tutup
                </button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const emailData = @json($emailData);

        function openDetailModal(index) {
            const email = emailData[index];
            document.getElementById('detailSubject').innerText = email.subject;
            document.getElementById('detailFrom').innerText = email.from;
            document.getElementById('detailDate').innerText = email.date;
            document.getElementById('detailBody').innerText = email.body;

            const modal = document.getElementById('detailModal');
            modal.classList.remove('hidden');
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }
    </script>
@endsection
