<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApupptFeedbackForm extends Model
{
    protected $table = 'apuppt_feedback_forms';

    protected $fillable = [
        'apuppt_pt_scope',
        'title',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function questions()
    {
        return $this->hasMany(ApupptFeedbackQuestion::class, 'form_id')->orderBy('order');
    }

    public function responses()
    {
        return $this->hasMany(ApupptFeedbackResponse::class, 'form_id');
    }
}
