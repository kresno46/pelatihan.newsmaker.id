@extends('layouts.app')

@section('namePage', 'Kuesioner Feedback APUPPT')

@section('content')
    <header class="w-full bg-white dark:bg-gray-800 shadow rounded-lg mb-5 p-4 sm:p-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    Kuesioner Feedback
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Kuesioner ini wajib diisi trainer {{ $selectedRole }} setelah mengunduh sertifikat APUPPT.
                </p>
            </div>

            <div class="flex items-center gap-2">
                @if (session('success'))
                    <div class="text-green-600 dark:text-green-400 font-semibold">{{ session('success') }}</div>
                @endif

                <a href="{{ route('apuppt.feedback.report', $form) }}"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-md font-medium transition">
                    Lihat Laporan
                </a>
            </div>
        </div>
    </header>

    @if (! $forcedRole)
        <div class="mb-4 p-4 bg-white dark:bg-gray-800 rounded-xl shadow">
            <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Pilih PT</label>
            <form method="GET" class="flex gap-2">
                <select name="pt" onchange="this.form.submit()"
                    class="w-full max-w-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                    @foreach ($ptOptions as $pt)
                        <option value="{{ $pt }}" {{ $selectedRole === $pt ? 'selected' : '' }}>{{ $pt }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    @endif

    {{-- Form Info Kuesioner --}}
    <div class="mb-5 p-4 sm:p-5 bg-white dark:bg-gray-800 rounded-xl shadow">
        <form method="POST" action="{{ route('apuppt.feedback.update', $form) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-medium text-gray-900 dark:text-gray-100">Judul Kuesioner</label>
                <input type="text" name="title" value="{{ old('title', $form->title) }}"
                    class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror">
                @error('title')
                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block font-medium text-gray-900 dark:text-gray-100">Deskripsi</label>
                <textarea name="description" rows="2"
                    class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $form->description) }}</textarea>
                @error('description')
                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <div class="flex flex-col gap-2">
                    <label class="block font-medium text-gray-900 dark:text-gray-100">Status</label>
                    <select name="is_active"
                        class="border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="1" {{ old('is_active', $form->is_active ? '1' : '0') === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('is_active', $form->is_active ? '1' : '0') === '0' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>

                <button type="submit"
                    class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium">
                    Simpan
                </button>
            </div>
        </form>
    </div>

    {{-- Daftar Pertanyaan --}}
    <div class="p-4 sm:p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md" x-data="{
        openCreate: {{ $errors->hasBag('createQuestion') ? 'true' : 'false' }},
        openEdit: false,
        openDelete: false,
        edit: { id: null, question_text: '', type: 'rating', is_required: true, action: null },
        del: { action: null },
    }" x-cloak>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Daftar Pertanyaan</h3>
            <button type="button" @click="openCreate = true"
                class="bg-green-600 text-white px-3 py-2 rounded text-sm hover:bg-green-700">Tambah Pertanyaan</button>
        </div>

        @if ($form->questions->isEmpty())
            <p class="text-gray-500 dark:text-gray-400">Belum ada pertanyaan.</p>
        @else
            <div class="space-y-2">
                @foreach ($form->questions as $q)
                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg overflow-hidden">
                        <div class="flex items-stretch">
                            <div
                                class="w-14 border-r bg-gray-200 border-gray-200 dark:border-gray-600 dark:bg-gray-700 text-center flex items-center justify-center font-semibold text-gray-800 dark:text-gray-100 select-none">
                                #{{ $loop->iteration }}
                            </div>

                            <div class="flex-1 p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <span
                                            class="inline-block text-xs font-medium px-2 py-0.5 rounded-full mb-1 {{ $q->type === 'rating' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $q->type === 'rating' ? 'Rating 1-5' : 'Esai' }}
                                        </span>
                                        @if ($q->is_required)
                                            <span class="inline-block text-xs font-medium px-2 py-0.5 rounded-full mb-1 bg-red-100 text-red-800">Wajib</span>
                                        @endif
                                        <p class="text-gray-900 dark:text-gray-100">{{ $q->question_text }}</p>
                                    </div>

                                    <div class="flex flex-col items-end gap-2 shrink-0">
                                        <div class="flex gap-1">
                                            <form method="POST" action="{{ route('apuppt.feedback.question.move', [$form, $q]) }}">
                                                @csrf
                                                <input type="hidden" name="direction" value="up">
                                                <button type="submit" class="px-2 py-1 text-xs rounded border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700" title="Naik">↑</button>
                                            </form>
                                            <form method="POST" action="{{ route('apuppt.feedback.question.move', [$form, $q]) }}">
                                                @csrf
                                                <input type="hidden" name="direction" value="down">
                                                <button type="submit" class="px-2 py-1 text-xs rounded border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700" title="Turun">↓</button>
                                            </form>
                                        </div>
                                        <div class="flex gap-3">
                                            <button type="button" class="text-blue-600 text-sm hover:underline"
                                                @click="openEdit = true; edit = {
                                                    id: {{ $q->id }},
                                                    question_text: @js($q->question_text),
                                                    type: @js($q->type),
                                                    is_required: {{ $q->is_required ? 'true' : 'false' }},
                                                    action: '{{ route('apuppt.feedback.question.update', [$form, $q]) }}'
                                                }">
                                                Edit
                                            </button>
                                            <button type="button" class="text-red-600 text-sm hover:underline"
                                                @click="openDelete = true; del = { action: '{{ route('apuppt.feedback.question.destroy', [$form, $q]) }}' }">
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Modal Tambah Pertanyaan --}}
        <div x-show="openCreate" x-transition @keydown.escape.window="openCreate=false"
            class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/50" @click="openCreate=false"></div>
            <div class="relative w-full max-w-lg bg-white dark:bg-gray-800 rounded-xl shadow-xl">
                <div class="flex items-center justify-between px-5 py-4 border-b dark:border-gray-700">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Tambah Pertanyaan</h3>
                    <button type="button" @click="openCreate=false"
                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">✕</button>
                </div>

                <form action="{{ route('apuppt.feedback.question.store', $form) }}" method="POST" class="p-5 space-y-4">
                    @csrf
                    <div>
                        <label class="block font-medium text-gray-900 dark:text-gray-100">Pertanyaan</label>
                        <textarea name="question_text" rows="3"
                            class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-green-600 @error('question_text', 'createQuestion') border-red-500 @enderror">{{ old('question_text') }}</textarea>
                        @error('question_text', 'createQuestion')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-medium text-gray-900 dark:text-gray-100">Tipe</label>
                        <select name="type"
                            class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-green-600">
                            <option value="rating">Rating (1-5)</option>
                            <option value="essay">Esai</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_required" value="1" id="create-required" checked
                            class="rounded border-gray-300 dark:border-gray-600">
                        <label for="create-required" class="text-gray-900 dark:text-gray-100">Wajib diisi</label>
                    </div>

                    <div class="flex items-center justify-end gap-2">
                        <button type="button" @click="openCreate=false"
                            class="px-4 py-2 rounded border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded bg-green-600 hover:bg-green-700 text-white">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Edit Pertanyaan --}}
        <div x-show="openEdit" x-transition @keydown.escape.window="openEdit=false"
            class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/50" @click="openEdit=false"></div>
            <div class="relative w-full max-w-lg bg-white dark:bg-gray-800 rounded-xl shadow-xl">
                <div class="flex items-center justify-between px-5 py-4 border-b dark:border-gray-700">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Edit Pertanyaan</h3>
                    <button type="button" @click="openEdit=false"
                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">✕</button>
                </div>

                <form x-bind:action="edit.action" method="POST" class="p-5 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block font-medium text-gray-900 dark:text-gray-100">Pertanyaan</label>
                        <textarea name="question_text" rows="3" x-model="edit.question_text"
                            class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-600"></textarea>
                    </div>

                    <div>
                        <label class="block font-medium text-gray-900 dark:text-gray-100">Tipe</label>
                        <select name="type" x-model="edit.type"
                            class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <option value="rating">Rating (1-5)</option>
                            <option value="essay">Esai</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_required" value="1" x-model="edit.is_required" id="edit-required"
                            class="rounded border-gray-300 dark:border-gray-600">
                        <label for="edit-required" class="text-gray-900 dark:text-gray-100">Wajib diisi</label>
                    </div>

                    <div class="flex items-center justify-end gap-2">
                        <button type="button" @click="openEdit=false"
                            class="px-4 py-2 rounded border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Hapus Pertanyaan --}}
        <div x-show="openDelete" x-transition @keydown.escape.window="openDelete=false"
            class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/50" @click="openDelete=false"></div>
            <div class="relative w-full max-w-md bg-white dark:bg-gray-800 rounded-xl shadow-xl">
                <div class="flex items-center justify-between px-5 py-4 border-b dark:border-gray-700">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Hapus Pertanyaan</h3>
                    <button type="button" @click="openDelete=false"
                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">✕</button>
                </div>
                <div class="p-5 space-y-4">
                    <p class="text-sm text-gray-700 dark:text-gray-300">Yakin mau menghapus pertanyaan ini?</p>
                    <form x-bind:action="del.action" method="POST" class="flex items-center justify-end gap-2">
                        @csrf
                        @method('DELETE')
                        <button type="button" @click="openDelete=false"
                            class="px-4 py-2 rounded border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded bg-red-600 hover:bg-red-700 text-white">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        [x-cloak] {
            display: none !important
        }
    </style>
@endsection
