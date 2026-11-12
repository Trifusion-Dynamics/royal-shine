<?php

namespace App\Models;

use App\Enums\GoldPurity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Product extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::saved(function ($product) {
            Cache::forget('home.page');
            Cache::forget('settings.all');
            Cache::forget('product.' . $product->slug);
        });

        static::deleted(function ($product) {
            Cache::forget('home.page');
            Cache::forget('settings.all');
            Cache::forget('product.' . $product->slug);
        });
    }

    protected $fillable = [
        'category_id',
        'collection_id',
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'metal_type',
        'gold_purity',
        'weight',
        'weight_unit',
        'stone_details',
        'price',
        'price_on_request',
        'is_featured',
        'status',
        'seo_title',
        'seo_description',
    ];

    protected $casts = [
        'gold_purity' => GoldPurity::class,
        'price_on_request' => 'boolean',
        'is_featured' => 'boolean',
        'weight' => 'decimal:3',
        'price' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }
}
