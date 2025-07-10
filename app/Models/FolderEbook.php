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
}
