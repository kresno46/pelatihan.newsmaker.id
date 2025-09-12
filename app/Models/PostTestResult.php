<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostTestResult extends Model
{
    use HasFactory;

    protected $table = 'post_test_results';

    protected $fillable = [
        'session_id',
        'user_id',
        'score',
        'title',
        'duration',
    ];

    public function session()
    {
        return $this->belongsTo(PostTestSession::class, 'session_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
