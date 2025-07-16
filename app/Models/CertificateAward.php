<?php
// app/Models/CertificateAward.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateAward extends Model
{
    protected $table = "certificate_awards";

    protected $fillable = [
        'user_id',
        'batch_number',
        'average_score',
        'total_ebooks',
        'certificate_uuid',
        'awarded_at',
    ];
}
