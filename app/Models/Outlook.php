<?php

// app/Models/Outlook.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Outlook extends Model
{
    use HasFactory;

    protected $table = 'outlooks';

    protected $fillable = [
        'title',
        'deskripsi',
        'cover',
        'file',
        'slug',
        'folderOutlook_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = Str::slug($model->title);
        });

        static::updating(function ($model) {
            $model->slug = Str::slug($model->title);
        });
    }

    public function folderOutlook()
    {
        return $this->belongsTo(FolderOutlook::class, 'folderOutlook_id');
    }
}
