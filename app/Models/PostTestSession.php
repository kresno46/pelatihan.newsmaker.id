<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostTestSession extends Model
{
    use HasFactory;

    protected $table = 'post_test_sessions';

    protected $fillable = [
        'ebook_id',
        'title',
        'duration',
    ];

    // Relasi ke Ebook
    public function ebook()
    {
        return $this->belongsTo(Ebook::class);
    }

    // Relasi ke soal-soal post test
    public function questions()
    {
        return $this->hasMany(PostTest::class, 'session_id');
    }

    public function getRouteKeyName()
    {
        return 'id'; // atau 'slug' jika kamu pakai slug, tapi kamu pakai id di sini
    }
}
