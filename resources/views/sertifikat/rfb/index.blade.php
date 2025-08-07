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
            background-image: url('{{ public_path('sertifikat-stamp/RFB/RFB.png') }}');
            background-size: cover;
            background-position: center;
        }

        table.layout {
            width: 100%;
            height: 100vh;
            border-collapse: collapse;
        }

        td {
            vertical-align: top;
            text-align: center;
            padding: 0 60px;
        }

        .content {
            padding-top: 270px;
        }

        .content .header {
            font-size: 20px
        }

        .name {
            margin-top: 10px;
            font-size: 34px;
            font-weight: bold;
            margin: 0;
            border-bottom: 1px solid #000;
            display: inline-block;
            padding: 0 30px;
        }

        .description {
            font-size: 22px;
        }

        .level-title {
            font-size: 20px;
            font-weight: bold;
        }

        .date-sign {
            font-size: 14px;
            text-align: center;
            padding-bottom: 40px;
        }

        .date {
            font-size: 18px;
        }

        .ttd {
            font-size: 18px;
            font-weight: bold;
        }

        .title {
            font-size: 14px;
        }

        .stamp {
            width: 200px;
            margin-top: 5px;
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

    <table class="layout">
        <tr>
            <td>
                <div class="content">
                    <p class="header">DIBERIKAN KEPADA:</p>
                    <p class="name">{{ $name }}</p>
                    <p class="description">Telah mengikuti:</p>
                    <p class="level-title">{{ $levelTitle }}</p>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="date-sign">
                    <p class="date">Jakarta, {{ $date }}</p>
                    <img class="stamp" src="{{ public_path('sertifikat-stamp/RFB/STAMP-RFB.png') }}" alt="Stamp">
                    <p class="ttd">Muhammad Barkah</p>
                    <hr class="signature-line">
                    <p class="title">Direktur Utama</p>
                    <div class="cert-id">ID Sertifikat: {{ $uuid }}</div>
                </div>
            </td>
        </tr>
    </table>

</body>

</html>
