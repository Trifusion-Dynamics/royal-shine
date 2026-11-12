<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Gallery extends Model
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
        'title',
        'description',
        'alt_text',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];
}
