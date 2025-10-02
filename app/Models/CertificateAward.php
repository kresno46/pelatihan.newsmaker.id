<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificateAward extends Model
{
    protected $table = "certificate_awards";

    protected $fillable = [
        'user_id',
        'batch_number',
        'post_test_id',
        'average_score',
        'certificate_uuid',
        'awarded_at',
    ];

    /**
     * Relasi ke tabel users
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke tabel post_test_results
     */
    public function postTestResult(): BelongsTo
    {
        return $this->belongsTo(PostTestResult::class, 'post_test_id');
    }
}
