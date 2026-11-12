<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Banner extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('home.page');
        });

        static::deleted(function () {
            Cache::forget('home.page');
        });
    }

    protected $fillable = [
        'image',
        'heading',
        'subheading',
        'cta_label',
        'cta_url',
        'status',
        'sort_order',
        'mobile_image',
        'alt_text',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];
}
