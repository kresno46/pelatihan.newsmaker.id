<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApupptPostTestQuestion extends Model
{
    use HasFactory;

    protected $table = 'apuppt_post_test_questions';

    protected $fillable = [
        'session_id',
        'question',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_option',
    ];

    public function session()
    {
        return $this->belongsTo(ApupptPostTestSession::class, 'session_id');
    }
}