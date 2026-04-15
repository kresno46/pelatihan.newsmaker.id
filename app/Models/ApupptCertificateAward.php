<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApupptCertificateAward extends Model
{
    protected $table = 'apuppt_certificate_awards';

    protected $fillable = [
        'user_id',
        'post_test_id',
        'average_score',
        'certificate_uuid',
        'awarded_at',
    ];

    protected $casts = [
        'awarded_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function postTestResult()
    {
        return $this->belongsTo(ApupptPostTestResult::class, 'post_test_id');
    }
}