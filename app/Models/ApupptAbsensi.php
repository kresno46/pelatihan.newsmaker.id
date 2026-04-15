<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApupptAbsensi extends Model
{
    use HasFactory;

    protected $table = 'apuppt_absensi_logs';

    protected $fillable = [
        'user_id',
        'jadwal_id',
        'waktu_absen',
    ];

    protected $casts = [
        'waktu_absen' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jadwal()
    {
        return $this->belongsTo(ApupptJadwalAbsensi::class, 'jadwal_id');
    }
}
