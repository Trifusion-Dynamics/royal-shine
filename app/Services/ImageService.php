<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ImageService
{
    public function url(?string $path): string
    {
        if (empty($path)) {
            return asset('images/placeholder.svg');
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
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
