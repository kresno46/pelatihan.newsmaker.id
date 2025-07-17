<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outlook extends Model
{
    use HasFactory;

    protected $fillable = [
        'folder_id',
        'title',
        'slug',
        'deskripsi',
        'cover',
        'file',
    ];

    public function folder()
    {
        return $this->belongsTo(OutlookFolder::class, 'folder_id');
    }

    public function postTestSessions()
    {
        return $this->hasMany(PostTestSession::class, 'ebook_id');
    }
}
