<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApupptPostTestSession extends Model
{
    use HasFactory;

    protected $table = 'apuppt_post_test_sessions';

    protected $fillable = [
        'title',
        'slug',
        'duration',
        'tipe',
        'status',
    ];

    protected static function booted()
    {
        static::creating(function ($session) {
            if (empty($session->slug)) {
                $session->slug = Str::slug($session->title) . '-' . uniqid();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function questions()
    {
        return $this->hasMany(ApupptPostTestQuestion::class, 'session_id');
    }

    public function results()
    {
        return $this->hasMany(ApupptPostTestResult::class, 'session_id');
    }

    public function jadwalAbsensis()
    {
        return $this->hasMany(ApupptJadwalAbsensi::class, 'apuppt_post_test_session_id');
    }
}