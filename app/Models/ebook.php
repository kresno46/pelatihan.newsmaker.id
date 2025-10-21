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
        'api_id',
        'api_data',
        'synced_at',
    ];

    protected $casts = [
        'api_data' => 'array',
        'synced_at' => 'datetime',
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
            // Kalau dari API dan slug sudah ada → biarin aja
            if ($ebook->isFromApi() && !empty($ebook->slug)) {
                return;
            }

            $ebook->slug = static::generateUniqueSlug($ebook->title);
        });

        // Buat slug baru saat updating, hanya kalau title berubah DAN bukan data API
        static::updating(function ($ebook) {
            if ($ebook->isDirty('title') && !$ebook->isFromApi()) {
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

    /**
     * Scope untuk ebook yang sudah di-sync dari API
     */
    public function scopeSyncedFromApi($query)
    {
        return $query->whereNotNull('api_id');
    }

    /**
     * Scope untuk ebook yang perlu di-sync
     */
    public function scopeNeedsSync($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('synced_at')
              ->orWhere('synced_at', '<', now()->subHours(1));
        });
    }

    /**
     * Check if ebook is from API
     */
    public function isFromApi()
    {
        return !is_null($this->api_id);
    }

    /**
     * Get API data attribute
     */
    public function getApiDataAttribute($value)
    {
        return $value ? json_decode($value, true) : null;
    }

    /**
     * Set API data attribute
     */
    public function setApiDataAttribute($value)
    {
        $this->attributes['api_data'] = $value ? json_encode($value) : null;
    }
    /**
 * Get the download URL for the ebook
 */
    public function getDownloadUrlAttribute()
    {
        if ($this->isFromApi() && !empty($this->file)) {
            // file dari API -> path relatif, tambahin base url
            return rtrim('https://ebook.newsmaker.id', '/') . '/' . ltrim($this->file, '/');
        }

        // file lokal
        if (!empty($this->file) && \Storage::disk('public')->exists('ebooks/' . $this->file)) {
            return asset('storage/ebooks/' . $this->file);
        }

        return null; // fallback kalau file gak ada
    }
}
