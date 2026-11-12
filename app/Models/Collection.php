<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Collection extends Model
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
        'name',
        'slug',
        'description',
        'image',
        'is_featured',
        'status',
        'seo_title',
        'seo_description',
        'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
