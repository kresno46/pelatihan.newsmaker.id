<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Sertifikat Pembelajaran Anda</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f9fafb; padding: 24px; margin: 0;">
    <div
        style="max-width: 600px; margin: auto; background-color: #ffffff; padding: 32px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">

        <!-- Judul -->
        <h2 style="font-size: 22px; color: #1f2937; margin-bottom: 24px;">
            🎉 Sertifikat Pembelajaran Telah Diterbitkan
        </h2>

        <!-- Sapaan -->
        <p style="font-size: 16px; color: #374151;">
            Halo <strong>{{ $name }}</strong>,
        </p>

        <!-- Isi kompleks -->
        <p style="font-size: 16px; color: #374151;">
            Selamat! Anda telah berhasil menyelesaikan seluruh modul pembelajaran pada <strong>Level
                {{ $levelTitle }}</strong>
            melalui platform edukasi kami. Pencapaian ini mencerminkan komitmen Anda dalam meningkatkan pengetahuan dan
            keterampilan secara berkelanjutan.
        </p>

        <p style="font-size: 16px; color: #374151;">
            Sebagai bentuk apresiasi atas pencapaian tersebut, kami lampirkan <strong>e-sertifikat resmi</strong>
            sebagai bukti partisipasi aktif dalam proses belajar.
            Sertifikat ini dapat Anda gunakan untuk kebutuhan pribadi, portofolio akademik, atau referensi profesional.
        </p>

        <!-- CTA Unduh -->
        <div style="text-align: center; margin: 36px 0;">
            <a href="{{ $downloadUrl ?? '#' }}"
                style="background-color: #2563eb; color: white; padding: 12px 24px; border-radius: 6px; font-weight: bold; text-decoration: none;">
                📥 Unduh Sertifikat Anda
            </a>
        </div>

        <!-- Ajakan login -->
        <p style="font-size: 16px; color: #374151;">
            Jangan berhenti di sini. Masuk kembali ke platform pembelajaran kami dan lanjutkan eksplorasi Anda di
            berbagai materi edukatif lainnya.
        </p>

        <div style="text-align: center; margin: 24px 0;">
            <a href="https://ebook.newsmaker.id/login"
                style="background-color: #10b981; color: white; padding: 10px 20px; border-radius: 6px; font-weight: bold; text-decoration: none;">
                📚 Masuk ke Platform Pembelajaran
            </a>
        </div>

        <!-- Penutup -->
        <p style="font-size: 16px; color: #374151;">
            Terima kasih telah menjadi bagian dari perjalanan pembelajaran ini. Kami berharap pengalaman ini memberikan
            nilai tambah untuk perkembangan Anda di masa mendatang.
        </p>

        <p style="font-size: 16px; color: #374151; margin-top: 32px;">
            Salam hangat,<br>
            <strong>Tim Newsmaker</strong>
        </p>
    </div>

    <!-- Footer -->
    <p style="text-align: center; font-size: 12px; color: #9ca3af; margin-top: 24px;">
        Email ini dikirim secara otomatis. Jika Anda merasa tidak terkait, silakan abaikan.
    </p>
</body>

</html>
