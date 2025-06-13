@props([
    'form' => 'formId',
    'label' => 'Ya, Simpan',
    'title' => 'Konfirmasi Simpan',
    'message' => 'Apakah Anda yakin ingin menyimpan data ini?',
])

<div id="modalSubmit"
    class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md">
        <!-- Header -->
        <div class="px-6 py-5">
            <h3 class="text-xl font-bold text-red-500">
                <i class="fa-solid fa-triangle-exclamation mr-2"></i>{{ $title }}
            </h3>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 border-t border-b dark:border-gray-700">
            <p class="text-gray-700 dark:text-gray-300">{{ $message }}</p>
        </div>

        <!-- Footer / Actions -->
        <div class="flex justify-end gap-3 px-6 py-4">
            <button onclick="document.getElementById('modalSubmit').classList.add('hidden')"
                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-md dark:bg-gray-600 dark:text-white">
                Batal
            </button>
            <button type="button" onclick="document.getElementById('{{ $form }}').submit()"
                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md">
                {{ $label }}
            </button>
        </div>
    </div>
</div>
