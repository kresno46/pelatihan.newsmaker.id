<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApupptFeedbackQuestion extends Model
{
    protected $table = 'apuppt_feedback_questions';

    protected $fillable = [
        'form_id',
        'question_text',
        'type',
        'is_required',
        'order',
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    public function form()
    {
        return $this->belongsTo(ApupptFeedbackForm::class, 'form_id');
    }

    public function answers()
    {
        return $this->hasMany(ApupptFeedbackAnswer::class, 'question_id');
    }
}
