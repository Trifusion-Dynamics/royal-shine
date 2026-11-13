<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ImageOptimization extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_path',
        'webp_path',
        'avif_path',
        'thumbnail_path',
        'medium_path',
        'large_path',
        'original_size',
        'optimized_size',
        'width',
        'height',
        'mime_type',
        'processing_status',
        'error_message',
    ];

    protected $casts = [
        'original_size' => 'integer',
        'optimized_size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    public function getUrl(?string $variant = null): string
    {
        if ($variant === 'webp' && $this->webp_path) {
            return app(\App\Services\ImageService::class)->url($this->webp_path);
        }

        if ($variant === 'avif' && $this->avif_path) {
            return app(\App\Services\ImageService::class)->url($this->avif_path);
        }

        if ($variant === 'thumbnail' && $this->thumbnail_path) {
            return app(\App\Services\ImageService::class)->url($this->thumbnail_path);
        }

        if ($variant === 'medium' && $this->medium_path) {
            return app(\App\Services\ImageService::class)->url($this->medium_path);
        }

        if ($variant === 'large' && $this->large_path) {
            return app(\App\Services\ImageService::class)->url($this->large_path);
        }

        return app(\App\Services\ImageService::class)->url($this->original_path);
    }

    public function getSavingsPercent(): ?float
    {
        if (!$this->original_size || !$this->optimized_size) {
            return null;
        }

        return round((1 - ($this->optimized_size / $this->original_size)) * 100, 2);
    }
}
