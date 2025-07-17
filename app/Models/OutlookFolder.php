<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutlookFolder extends Model
{
    use HasFactory;

    protected $table = 'outlook_folders';

    protected $fillable = [
        'folder_name',
        'slug',
        'deskripsi'
    ];

    public function outlooks()
    {
        return $this->hasMany(Outlook::class, 'folder_id');
    }
}
