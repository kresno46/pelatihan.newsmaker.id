<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApupptFeatureSetting extends Model
{
    protected $table = 'apuppt_feature_settings';

    protected $fillable = [
        'is_enabled',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public static function isEnabled(): bool
    {
        $value = static::query()->value('is_enabled');

        return $value === null ? true : (bool) $value;
    }
}
