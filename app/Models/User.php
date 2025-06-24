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
        'password',
        'role',
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
    public function earnedCertificateBatches($minAvg = 75): int
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
}
