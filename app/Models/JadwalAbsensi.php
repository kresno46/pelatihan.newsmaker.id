<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalAbsensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'tanggal',
        'is_open',
        'post_test_session_id'
    ];

    /**
     * Relasi ke Absensi
     */
    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'jadwal_id');
    }
}
