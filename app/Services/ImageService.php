<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\ImageOptimization;

class ImageService
{
    public function url(?string $path, ?string $variant = null): string
    {
        if (empty($path)) {
            return asset('images/placeholder.svg');
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        // Check if there's an ImageOptimization record with optimized variants
        $opt = ImageOptimization::where('original_path', $path)->first();

        if ($opt && $variant && $opt->{$variant . '_path'}) {
            return Storage::disk(config('royal_shine.upload_disk'))->url($opt->{$variant . '_path'});
        }

        // Return WebP version if available (best format for web)
        if ($opt && $opt->webp_path) {
            return Storage::disk(config('royal_shine.upload_disk'))->url($opt->webp_path);
        }

        // Return AVIF if available (even better compression)
        if ($opt && $opt->avif_path) {
            return Storage::disk(config('royal_shine.upload_disk'))->url($opt->avif_path);
        }

        // Check public folder first (for images copied from public/images/)
        $publicPath = public_path($path);
        if (File::exists($publicPath)) {
            return asset($path);
        }

        // Check storage disk
        $disk = Storage::disk(config('royal_shine.upload_disk'));

        if ($disk->exists($path)) {
            return $disk->url($path);
        }

        // Check storage/app/public folder directly
        $storagePath = storage_path('app/public/' . $path);
        if (File::exists($storagePath)) {
            return asset('storage/' . $path);
        }

        return asset('images/placeholder.svg');
    }

    public function dimensions(?string $path): array
    {
        if (empty($path)) {
            return ['width' => 400, 'height' => 400];
        }

        // Check ImageOptimization record for dimensions
        $opt = ImageOptimization::where('original_path', $path)->first();
        if ($opt && $opt->width && $opt->height) {
            return ['width' => $opt->width, 'height' => $opt->height];
        }

        // Check public folder first
        $publicPath = public_path($path);
        if (File::exists($publicPath)) {
            $size = getimagesize($publicPath);
            if ($size !== false) {
                return ['width' => $size[0], 'height' => $size[1]];
            }
        }

        // Check storage disk
        $disk = Storage::disk(config('royal_shine.upload_disk'));

        if ($disk->exists($path)) {
            $size = getimagesizefromstring($disk->get($path));
            if ($size !== false) {
                return ['width' => $size[0], 'height' => $size[1]];
            }
        }

        return ['width' => 400, 'height' => 400];
    }
}
