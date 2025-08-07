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
     * Relasi ke tabel folder_ebooks (batch_number → id)
     */
    public function folder(): BelongsTo
    {
        return $this->belongsTo(FolderEbook::class, 'batch_number');
    }
}
