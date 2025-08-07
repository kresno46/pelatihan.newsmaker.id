<?php

// app/Models/FolderOutlook.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FolderOutlook extends Model
{
    use HasFactory;

    protected $table = 'folder_outlooks'; // pakai S

    protected $fillable = [
        'cover_folder',
        'folder_name',
        'deskripsi',
        'slug',
        'position',
        'category',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = Str::slug($model->folder_name);
        });

        static::updating(function ($model) {
            $model->slug = Str::slug($model->folder_name);
        });
    }

    public function outlooks()
    {
        return $this->hasMany(Outlook::class, 'folderOutlook_id');
    }
}
