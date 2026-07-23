<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApupptFeedbackAnswer extends Model
{
    protected $table = 'apuppt_feedback_answers';

    protected $fillable = [
        'response_id',
        'question_id',
        'rating_value',
        'answer_text',
    ];

    public function response()
    {
        return $this->belongsTo(ApupptFeedbackResponse::class, 'response_id');
    }

    public function question()
    {
        return $this->belongsTo(ApupptFeedbackQuestion::class, 'question_id');
    }
}
