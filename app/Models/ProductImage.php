<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::saved(function (ProductImage $image): void {
            if ($image->is_primary) {
                static::query()
                    ->where('product_id', $image->product_id)
                    ->whereKeyNot($image->id)
                    ->update(['is_primary' => false]);

                return;
            }

            $hasPrimary = static::query()
                ->where('product_id', $image->product_id)
                ->where('is_primary', true)
                ->exists();

            if (! $hasPrimary) {
                static::query()->whereKey($image->id)->update(['is_primary' => true]);
            }
        });

        static::deleted(function (ProductImage $image): void {
            if ($image->is_primary) {
                static::query()
                    ->where('product_id', $image->product_id)
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->limit(1)
                    ->update(['is_primary' => true]);
            }

            if ($image->image && ! filter_var($image->image, FILTER_VALIDATE_URL)) {
                Storage::disk(config('royal_shine.upload_disk'))->delete($image->image);
            }
        });
    }

    protected $fillable = [
        'product_id',
        'image',
        'alt_text',
        'sort_order',
        'is_primary',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_primary' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
