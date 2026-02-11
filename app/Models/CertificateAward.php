<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\PostTestResult;

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

    protected $casts = [
        'awarded_at' => 'datetime',
    ];

    /**
     * Relasi ke tabel users
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke tabel folder_ebooks (batch_number → id)
     */
    public function folder(): BelongsTo
    {
        return $this->belongsTo(FolderEbook::class, 'batch_number');
    }

    /**
     * Relasi ke tabel post_test_results (post_test_id -> id)
     */
    public function postTestResult(): BelongsTo
    {
        return $this->belongsTo(PostTestResult::class, 'post_test_id');
    }
}
