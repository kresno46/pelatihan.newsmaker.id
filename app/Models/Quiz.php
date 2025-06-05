<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Quiz extends Model
{
    use HasFactory;

    protected $table = 'quizzes';

    // Field yang bisa diisi (fillable)
    protected $fillable = [
        'title',
        'slug',
        'description',
        'Status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($quiz) {
            $quiz->slug = Str::slug($quiz->title);
        });

        static::updating(function ($quiz) {
            $quiz->slug = Str::slug($quiz->title);
        });
    }

    /**
     * Relasi: Satu kuis memiliki banyak pertanyaan.
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Relasi: Satu kuis memiliki banyak hasil pengerjaan oleh user.
     */
    public function results()
    {
        return $this->hasMany(UserQuizResult::class);
    }
}
