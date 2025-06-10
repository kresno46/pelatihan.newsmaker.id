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
}
