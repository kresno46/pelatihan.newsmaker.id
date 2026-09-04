<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', Times, serif;
            background-image: url('{{ public_path('sertifikat-stamp/EWF/EWF-PATL.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            width: 297mm;
            height: 210mm;
            overflow: hidden;
        }

        .certificate-page {
            position: relative;
            width: 297mm;
            height: 210mm;
            overflow: hidden;
        }

        p {
            margin: 0;
        }

        .content {
            position: absolute;
            top: 72mm;
            left: 0;
            right: 0;
            text-align: center;
            padding: 0 60px;
        }

        .content .header {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .name {
            font-size: 34px;
            font-weight: bold;
            border-bottom: 1px solid #000;
            display: inline-block;
            padding: 0 30px;
            margin: 10px 0;
        }

        .description {
            font-size: 20px;
        }

        .level-title {
            font-size: 24px;
            font-weight: bold;
        }

        .date-sign {
            position: absolute;
            bottom: 10mm;
            left: 0;
            right: 0;
            font-size: 14px;
            text-align: center;
        }

        .date {
            font-size: 18px;
        }

        .stamp {
            width: 120px;
        }

        .ttd {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
        }

        .title {
            font-size: 16px;
        }

        .cert-id {
            font-size: 12px;
            color: #555;
            text-align: center;
            margin-top: 10px;
        }

        .signature-line {
            width: 200px;
            border: 1px solid #000;
            margin: 5px auto;
        }
    </style>
</head>

<body>

    <div class="certificate-page">
        <div class="content">
            <p class="header">DIBERIKAN KEPADA:</p>
            <p class="name">{{ ucwords(strtolower($name)) }}</p>
            <p class="description">Telah Mengikuti:</p>
            <p class="level-title">{{ $levelTitle }}</p>
        </div>

        <div class="date-sign">
            <p class="date">Jakarta, {{ $date }}</p>
            <img class="stamp" src="{{ public_path('sertifikat-stamp/EWF/STAMP EWF.png') }}" alt="Stamp">
            <p class="ttd">FADLY KHAIRUZZADHI, M.H.</p>
            <hr class="signature-line">
            <p class="title">Direktur Utama</p>
            <div class="cert-id">ID Sertifikat: {{ $uuid }}</div>
        </div>
    </div>

</body>

</html>
