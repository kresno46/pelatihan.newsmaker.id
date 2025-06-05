<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sertifikat - {{ $nama ?? 'Peserta' }}</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Print/PDF Styling -->
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            height: 100%;
        }
    </style>
</head>

<body class="bg-gray-200 flex items-center justify-center min-h-screen">

    <!-- Sertifikat -->
    <div
        class="bg-white w-[297mm] h-[210mm] p-12 border-8 border-yellow-500 rounded-2xl shadow-2xl flex flex-col justify-between">

        <!-- HEADER -->
        <div class="text-center">
            <h1 class="text-6xl font-extrabold text-yellow-600 mb-4">SERTIFIKAT</h1>
            <p class="text-xl text-gray-700">Diberikan kepada</p>
            <h2 class="text-5xl font-bold text-gray-900 my-4">{{ $nama ?? 'Nama Peserta' }}</h2>
            <p class="text-xl text-gray-700 italic">
                Atas keberhasilannya menyelesaikan kuis<br>
                <span class="text-yellow-600 font-bold">{{ $judul ?? 'Judul Kuis' }}</span><br>
                pada tanggal {{ $tanggal ?? now()->format('d F Y') }}
            </p>
        </div>

        <!-- FOOTER -->
        <div class="flex justify-between items-end px-16 mt-8">
            <div class="text-left">
                <p class="font-semibold text-gray-600">Penyelenggara</p>
                <div class="mt-24 border-t-2 border-yellow-500 w-60"></div>
            </div>
            <div class="text-right">
                <p class="font-semibold text-gray-600">Tanda Tangan</p>
                <div class="mt-24 border-t-2 border-yellow-500 w-60 mx-auto"></div>
            </div>
        </div>

    </div>

    <!-- Tombol Print -->
    <div class="absolute top-4 right-4">
        <button onclick="window.print()"
            class="px-4 py-2 bg-yellow-500 text-white rounded shadow hover:bg-yellow-600 print:hidden">
            Download / Cetak
        </button>
    </div>

</body>

</html>