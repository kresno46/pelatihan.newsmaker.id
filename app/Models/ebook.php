<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ebook extends Model
{
    use HasFactory;

    protected $table = 'ebooks';

    protected $fillable = [
        'judul',
        'deskripsi',
        'penulis',
        'tahun_terbit',
        'file_ebook',
        'cover_image',
    ];
}
