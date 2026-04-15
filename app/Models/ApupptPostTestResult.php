<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApupptPostTestResult extends Model
{
    use HasFactory;

    protected $table = 'apuppt_post_test_results';

    protected $fillable = [
        'session_id',
        'user_id',
        'score',
    ];

    public function session()
    {
        return $this->belongsTo(ApupptPostTestSession::class, 'session_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}