<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostTestSession extends Model
{
    use HasFactory;

    // Nama tabel (optional jika sesuai konvensi Laravel)
    protected $table = 'post_test_sessions';

    // Mass Assignment
    protected $fillable = [
        'ebook_id',
        'title',
        'duration',
    ];

    /**
     * Relasi: Sesi ini milik sebuah Ebook
     */
    public function ebook()
    {
        return $this->belongsTo(Ebook::class, 'ebook_id');
    }

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



    /**
     * Override key untuk route model binding (optional)
     */
    public function getRouteKeyName()
    {
        return 'id';  // Tetap pakai 'id' karena route kamu passing sessionId numerik, bukan slug
    }
}
