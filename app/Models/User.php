<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    public const APUPPT_PT_ROLES = [
        'Trainer (RFB)',
        'Trainer (SGB)',
        'Trainer (KPF)',
        'Trainer (BPF)',
        'Trainer (EWF)',
    ];

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
        'alamat',
        'no_tlp',
        'jabatan',
        'password',
        'role',
        'apuppt_pt_scope',
        'cabang',
        'email_verified_at',
        'last_login_at',
        'suspended_at',
        'force_password_reset',
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
        'last_login_at' => 'datetime',
        'suspended_at' => 'datetime',
        'force_password_reset' => 'boolean',
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
     * @param  int  $minAvg  Nilai rata-rata minimum untuk dapat sertifikat
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
            if ($chunk->count() < 10) {
                break;
            }

            $avg = $chunk->avg('score');
            if ($avg >= $minAvg) {
                $validBatches++;
            }
        }

        return $validBatches;
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

    public function isApupptAdmin(): bool
    {
        return $this->role === 'Admin APUPPT';
    }
}
