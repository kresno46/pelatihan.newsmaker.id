<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApupptEbook extends Model
{
    use HasFactory;

    protected $table = 'apuppt_ebooks';

    protected $fillable = [
        'folder_id',
        'title',
        'slug',
        'deskripsi',
        'cover',
        'file',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ebook) {
            $ebook->slug = static::generateUniqueSlug($ebook->title);
        });

        static::updating(function ($ebook) {
            if ($ebook->isDirty('title')) {
                $ebook->slug = static::generateUniqueSlug($ebook->title, $ebook->id);
            }
        });
    }

    protected static function generateUniqueSlug($title, $exceptId = null)
    {
        $slug = Str::slug($title);
        $query = static::where('slug', 'like', "{$slug}%");
        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }

        $count = $query->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        return $slug;
    }

    public function folder()
    {
        return $this->belongsTo(ApupptEbookFolder::class, 'folder_id');
    }
}