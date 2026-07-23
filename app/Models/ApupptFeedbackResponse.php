<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApupptFeedbackResponse extends Model
{
    protected $table = 'apuppt_feedback_responses';

    protected $fillable = [
        'form_id',
        'user_id',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function form()
    {
        return $this->belongsTo(ApupptFeedbackForm::class, 'form_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function answers()
    {
        return $this->hasMany(ApupptFeedbackAnswer::class, 'response_id');
    }
}
