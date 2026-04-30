<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApupptEbookFolder extends Model
{
    use HasFactory;

    protected $table = 'apuppt_ebook_folders';

    protected $fillable = [
        'folder_name',
        'deskripsi',
        'slug',
        'is_active',
        'apuppt_pt_scope',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($folder) {
            $folder->slug = Str::slug($folder->folder_name);
        });

        static::updating(function ($folder) {
            $folder->slug = Str::slug($folder->folder_name);
        });
    }

    public function ebooks()
    {
        return $this->hasMany(ApupptEbook::class, 'folder_id');
    }
}
