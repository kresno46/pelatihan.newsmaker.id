<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuizResult extends Model
{
    use HasFactory;

    protected $table = 'user_quiz_results';

    // Field yang bisa diisi
    protected $fillable = [
        'user_id',
        'quiz_id',
        'score',
    ];

    /**
     * Relasi: Hasil kuis ini dimiliki oleh satu user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Hasil kuis ini berasal dari satu kuis tertentu.
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
