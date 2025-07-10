<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ebook extends Model
{
    use HasFactory;

    protected $table = 'ebooks';

    protected $fillable = [
        'title',
        'slug',
        'deskripsi',
        'cover',
        'file',
        'folder_id',
    ];

    public function postTestSessions()
    {
        return $this->hasMany(PostTestSession::class, 'ebook_id');
    }

    // Relasi folder ebook
    public function folderEbook()
    {
        return $this->belongsTo(FolderEbook::class, 'folder_id');
    }

    /**
     * Hook untuk membuat slug otomatis saat model dibuat / diupdate.
     */
    protected static function boot()
    {
        parent::boot();

        // Buat slug saat creating
        static::creating(function ($ebook) {
            $ebook->slug = static::generateUniqueSlug($ebook->title);
        });

        // Buat slug baru saat updating, hanya jika title berubah
        static::updating(function ($ebook) {
            if ($ebook->isDirty('title')) {
                $ebook->slug = static::generateUniqueSlug($ebook->title, $ebook->id);
            }
        });
    }

    /**
     * Generate slug unik berdasarkan judul.
     *
     * @param string $title
     * @param int|null $exceptId
     * @return string
     */
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
}
