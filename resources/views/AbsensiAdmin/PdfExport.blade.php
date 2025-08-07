<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Daftar Absensi</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 16px;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #00b7ff;
            color: white;
        }
    </style>
</head>

<body>
    @php
        \Carbon\Carbon::setLocale('id');
    @endphp

    <div class="header">
        <h2>Daftar Kehadiran - {{ $jadwal->title }}</h2>
        <p>
            Berikut adalah daftar peserta yang telah melakukan absensi untuk kegiatan
            <strong>{{ $jadwal->title }}</strong>.
            Data meliputi nama peserta, asal perusahaan, dan waktu absensi.
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama</th>
                <th>Perusahaan</th>
                <th>Waktu Absen</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($absensiList as $absen)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ optional($absen->user)->name ?? '-' }}</td>
                    <td>{{ optional($absen->user)->nama_perusahaan ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($absen->waktu_absen)->translatedFormat('d F Y - H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Belum ada data absensi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
