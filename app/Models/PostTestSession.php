<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PostTestSession extends Model
{
    use HasFactory;

    protected $table = 'post_test_sessions';

    protected $fillable = [
        'ebook_id',
        'title',
        'slug',
        'duration',
        'tipe',
        'status',
        'post_test_session_id',
    ];

    /**
     * Relasi: Sesi ini memiliki banyak pertanyaan (PostTest)
     */
    public function questions()
    {
        return $this->hasMany(PostTest::class, 'session_id');
    }

    public function results()
    {
        return $this->hasMany(PostTestResult::class, 'session_id');
    }

    public function jadwalAbsensis()
    {
        return $this->hasMany(JadwalAbsensi::class, 'post_test_session_id');
    }

    public function ebook()
    {
        return $this->belongsTo(Ebook::class, 'ebook_id');
    }

    /**
     * Generate slug otomatis dari title saat create
     */
    protected static function booted()
    {
        static::creating(function ($session) {
            $session->slug = Str::slug($session->title) . '-' . uniqid();
        });
    }

    /**
     * Override key untuk route model binding → pakai slug
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
