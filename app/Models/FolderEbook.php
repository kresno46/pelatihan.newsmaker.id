<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FolderEbook extends Model
{
    use HasFactory;

    protected $table = 'folder_ebooks';

    protected $fillable = [
        'folder_name',
        'deskripsi',
        'slug',
        'api_id',
        'api_data',
        'synced_at',
    ];

    protected $casts = [
        'api_data' => 'array',
        'synced_at' => 'datetime',
    ];

    /**
     * Generate slug otomatis saat membuat dan mengupdate.
     */
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

    /**
     * Relasi: Satu folder punya banyak eBook.
     * Pastikan kolom foreign key di tabel eBooks adalah 'folder_id'
     */
    public function ebooks()
    {
        return $this->hasMany(Ebook::class, 'folder_id');
    }

    /**
     * Scope untuk folder yang sudah di-sync dari API
     */
    public function scopeSyncedFromApi($query)
    {
        return $query->whereNotNull('api_id');
    }

    /**
     * Scope untuk folder yang perlu di-sync
     */
    public function scopeNeedsSync($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('synced_at')
              ->orWhere('synced_at', '<', now()->subHours(1));
        });
    }

    /**
     * Check if folder is from API
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
}
