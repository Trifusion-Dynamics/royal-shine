<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ImageService
{
    public function url(?string $path): string
    {
        if (empty($path)) {
            return asset('images/placeholder.svg');
        }

        if (Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }

        return $path;
    }

    public function dimensions(?string $path): array
    {
        if (empty($path)) {
            return ['width' => 400, 'height' => 400];
        }

        $disk = 'public';

        if (! Storage::disk($disk)->exists($path)) {
            return ['width' => 400, 'height' => 400];
        }

        $localPath = Storage::disk($disk)->path($path);

        if (! file_exists($localPath)) {
            return ['width' => 400, 'height' => 400];
        }

        $size = @getimagesize($localPath);

        if ($size === false) {
            return ['width' => 400, 'height' => 400];
        }

        return ['width' => $size[0], 'height' => $size[1]];
    }
}
