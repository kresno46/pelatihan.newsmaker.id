<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\CertificateAward;
use App\Models\PostTestResult;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'warga_negara',
        'alamat',
        'no_id',
        'no_tlp',
        'pekerjaan',
        'jabatan',
        'password',
        'role',
        'cabang',
    ];

    /**
     * Atribut yang harus disembunyikan saat serialisasi.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'tanggal_lahir' => 'date',
        'password' => 'hashed',
    ];

    /**
     * Relasi ke CertificateAward.
     */
    public function certificateAwards()
    {
        return $this->hasMany(CertificateAward::class);
    }

    /**
     * Menghitung berapa batch sertifikat yang telah memenuhi syarat (per 10 eBook dengan avg >= 75).
     *
     * @param int $minAvg Nilai rata-rata minimum untuk dapat sertifikat
     * @return int Jumlah batch sertifikat yang bisa diperoleh
     */
    public function earnedCertificateBatches($minAvg = 60): int
    {
        $results = PostTestResult::where('user_id', $this->id)
            ->groupBy('ebook_id')
            ->selectRaw('MAX(score) as score, ebook_id')
            ->orderByDesc('score')
            ->get();

        $chunks = $results->chunk(10);
        $validBatches = 0;

        foreach ($chunks as $chunk) {
            if ($chunk->count() < 10) break;

            $avg = $chunk->avg('score');
            if ($avg >= $minAvg) {
                $validBatches++;
            }
        }

        return $validBatches;
    }

    /**
     * Mengecek apakah user sudah menyelesaikan semua post-test dalam sebuah folder (materi).
     *
     * @param int $folderId
     * @return bool
     */
    public function hasCompletedFolder($folderId)
    {
        // Ambil semua eBook dalam folder tersebut
        $ebookIds = \App\Models\Ebook::where('folder_id', $folderId)->pluck('id')->toArray();

        // Ambil semua eBook yang sudah dikerjakan oleh user
        $userEbookIds = PostTestResult::where('user_id', $this->id)
            ->whereIn('ebook_id', $ebookIds)
            ->pluck('ebook_id')
            ->unique()
            ->toArray();

        // Cek apakah semua eBook sudah dikerjakan
        return count(array_diff($ebookIds, $userEbookIds)) === 0;
    }

    // App\Models\User.php
    public function getNamaPerusahaanAttribute()
    {
        return match ($this->role) {
            'Trainer (RFB)' => 'PT Rifan Financindo Berjangka',
            'Trainer (SGB)' => 'PT Solid Gold Berjangka',
            'Trainer (KPF)' => 'PT Kontak Perkasa Futures',
            'Trainer (BPF)' => 'PT Best Profit Futures',
            'Trainer (EWF)' => 'PT Equity World Futures',
            default => $this->role,
        };
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }
}
