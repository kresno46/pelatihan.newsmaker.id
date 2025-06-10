<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostTest extends Model
{
    use HasFactory;

    protected $table = 'post_tests';

    protected $fillable = [
        'session_id',
        'question',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_option',
    ];

    // Relasi ke PostTestSession
    public function session()
    {
        return $this->belongsTo(PostTestSession::class, 'session_id');
    }
}
