<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Sertifikat Penghargaan</title>
    <style>
        @font-face {
            font-family: 'OpenSans';
            font-style: normal;
            font-weight: normal;
            src: url("{{ public_path('fonts/OpenSans-Regular.ttf') }}") format('truetype');
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'OpenSans', sans-serif;
            background-image: url('{{ public_path('Biru Putih Estetik Modern Sertifikat Dokumen A4 .png') }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }

        .content {
            padding: 80px;
            text-align: center;
            color: #333;
        }

        .certificate-title {
            font-size: 28px;
            font-weight: bold;
            margin-top: 40px;
            margin-bottom: 30px;
        }

        .certificate-body {
            font-size: 16px;
            margin-bottom: 40px;
        }

        .logo {
            width: 150px;
            margin-bottom: 20px;
        }

        .id-sertifikat {
            font-size: 10px;
            margin-top: 25px;
            color: #555;
        }

        .signature {
            margin-top: 60px;
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="content">
        <!-- Logo -->
        <img class="logo" src="{{ public_path('assets/NewsMaker-23-logo.png') }}" alt="Logo Newsmaker">

        <!-- Judul Sertifikat -->
        <div class="certificate-title">Sertifikat Penghargaan</div>

        <!-- Isi Sertifikat -->
        <div class="certificate-body">
            <p>Sertifikat ini diberikan kepada</p>
            <h1><strong>{{ $name ?? 'Nama Peserta' }}</strong></h1>
            <p class="max-w-md">sebagai bentuk penghargaan atas pencapaian menyelesaikan pembelajaran dengan baik pada
                level
                <strong>{{ $levelTitle ?? 'Level Tidak Diketahui' }}</strong>.
            </p>
        </div>

        <!-- Tanggal dan Penandatangan -->
        <div>
            <p>Jakarta, {{ $date ?? \Carbon\Carbon::now()->format('d F Y') }}</p>
            <div class="signature">Newsmaker 23</div>
        </div>

        <!-- ID Sertifikat -->
        <p class="id-sertifikat"><strong>ID Sertifikat:</strong> {{ $uuid ?? '12345678-uuid' }}</p>
    </div>
</body>

</html>
