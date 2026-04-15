<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApupptJadwalAbsensi extends Model
{
    use HasFactory;

    protected $table = 'apuppt_jadwal_absensis';

    protected $fillable = [
        'title',
        'tanggal',
        'is_open',
        'apuppt_post_test_session_id',
    ];

    protected $casts = [
        'is_open' => 'boolean',
        'tanggal' => 'date',
    ];

    public function absensis()
    {
        return $this->hasMany(ApupptAbsensi::class, 'jadwal_id');
    }

    public function postTestSession()
    {
        return $this->belongsTo(ApupptPostTestSession::class, 'apuppt_post_test_session_id');
    }
}